<?php

namespace modelo;

use app\client\Storage;
use bd\Formatos;
use bd\My;
use DateTime;
use Exception;

use function token;

class Workspace
{
    public int $codigo;
    public int $codCriador;
    public string $hash;
    public string $nome = '';
    public ?string $descricao = null;
    public ?string $logo = null;
    public ?string $whatsApp = null;
    public ?string $email = null;
    public ?string $cep = null;
    public ?string $endereco = null;
    public ?string $numero = null;
    public ?string $complemento = null;
    public ?string $bairro = null;
    public ?string $uf = null;
    public ?string $cidade = null;
    public bool $ativo = true;
    public string $criacao;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if (!$codigo) {
            return;
        }
        $c = My::con();
        $query = <<< CONSTROI
            select cod_criador, hash, nome, descricao, cep, endereco, numero,
                   complemento, bairro, uf, cidade, logo, ativo, criacao
            from workspace
            where codigo = $codigo
        CONSTROI;
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new Exception('Workspace não encontrado');
        }
        $this->codCriador = $l['cod_criador'];
        $this->hash = $l['hash'];
        $this->nome = $l['nome'];
        $this->descricao = $l['descricao'];
        $this->cep = Formatos::cepApp($l['cep']);
        $this->endereco = $l['endereco'];
        $this->numero = $l['numero'];
        $this->complemento = $l['complemento'];
        $this->bairro = $l['bairro'];
        $this->uf = $l['uf'];
        $this->cidade = $l['cidade'];
        $this->logo = $l['logo'];
        $this->ativo = $l['ativo'] == 1;
        $this->criacao = $l['criacao'];
    }

    public function save(): void
    {
        if (!$this->codigo) {
            $this->insert();
        } else {
            $this->update();
        }
    }

    private function insert(): void
    {
        $c = My::con();
        $query = <<< INSERE
            INSERT INTO workspace
                (cod_criador, hash, nome, descricao, cep, endereco, numero,
                 complemento, bairro, uf, cidade, logo, ativo, criacao)
            values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        INSERE;
        $com = $c->prepare($query);
        $com->execute([
            $this->codCriador,
            $this->hash,
            $this->nome,
            $this->descricao,
            $this->cep,
            $this->endereco,
            $this->numero,
            $this->complemento,
            $this->bairro,
            $this->uf,
            $this->cidade,
            $this->logo,
            $this->ativo,
            $this->criacao
        ]);
        $this->codigo = $com->insert_id;
    }

    private function update(): void
    {
        $c = My::con();
        $query = <<< UPDATE
            update workspace set
            nome = ?,
            descricao = ?,
            cep = ?,
            endereco = ?,
            numero = ?,
            complemento = ?,
            bairro = ?,
            uf = ?,
            cidade = ?,
            logo = ?,
            ativo = ?
            where codigo = ?
        UPDATE;
        $c->execute_query(
            $query,
            [
                $this->nome,
                $this->descricao,
                Formatos::cepBd($this->cep),
                $this->endereco,
                $this->numero,
                $this->complemento,
                $this->bairro,
                $this->uf,
                $this->cidade,
                $this->logo,
                $this->ativo == 1,
                $this->codigo
            ]
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generateProductsServices(): void
    {
        if ($this->hasProductsAndServices()) {
            return;
        }
        Produto::generate($this->codigo);
        Servico::generate($this->codigo);
    }

    private function hasProductsAndServices(): bool
    {
        $c = My::con();
        $query = <<< EXISTE_PRODUTOS
            select exists(
            select * from produtos p
               inner join categorias_produtos cp on p.cod_categoria = cp.codigo
            where cp.cod_workspace = $this->codigo) existe
        EXISTE_PRODUTOS;
        $existe = $c->execute_query($query)->fetch_assoc()['existe'] == 1;
        if (!$existe) {
            return false;
        }
        $query = <<< EXISTE_SERVICOS
            select exists(
            select * from servicos s
               inner join categorias_servicos cs on s.cod_categoria = cs.codigo
            where cs.cod_workspace = $this->codigo) existe
        EXISTE_SERVICOS;
        $existe = $c->execute_query($query)->fetch_assoc()['existe'] == 1;
        return $existe;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function remove(): void
    {
        $c = My::con();
        $c->query("delete from workspace where codigo = $this->codigo");
        if ($this->logo) {
            $storage = new Storage();
            $storage->fileDeleteByHash($this->logo);
        }
    }

    public static function generate(int $codCriador): Workspace
    {
        $ws = new Workspace(0);
        $ws->codCriador = $codCriador;
        $ws->hash = token(16);
        $ws->criacao = (new DateTime())->format('Y-m-d H:i:s');
        $ws->save();
        return $ws;
    }

    /**
     * @param int $codCriador
     * @return Workspace
     * @throws Exception
     */
    public static function getOrGenerate(int $codCriador): Workspace
    {
        $ws = self::porCriador($codCriador);
        if ($ws) {
            return $ws;
        }
        return self::generate($codCriador);
    }

    /**
     * @return array
     */
    public static function list(): array
    {
        $c = My::con();
        $query = <<< LISTA
            select w.codigo,
                   w.cod_criador,
                   u.nome criador,
                   w.hash,
                   w.nome,
                   w.descricao,
                   w.logo,
                   w.ativo,
                   w.criacao
            from workspace w
                 inner join usuario u on w.cod_criador = u.codigo
        LISTA;
        $r = $c->query($query);
        $wss = [];
        while ($w = $r->fetch_assoc()) {
            $wss[] = $w;
        }
        return $wss;
    }

    /**
     * @param int $codCriador
     * @return Workspace|null
     * @throws Exception
     */
    public static function porCriador(int $codCriador): ?Workspace
    {
        $c = My::con();
        $query = "select codigo from workspace where cod_criador = $codCriador order by codigo limit 1";
        $l = $c->execute_query($query)->fetch_assoc();
        if (!$l) {
            return null;
        }
        return new Workspace($l['codigo']);
    }

    /**
     * @param string $hash
     * @return Workspace
     * @throws Exception
     */
    public static function porHash(string $hash): Workspace
    {
        $c = My::con();
        $query = "select codigo from workspace where hash = ?";
        $l = $c->execute_query($query, [$hash])->fetch_assoc();
        if (!$l) {
            throw new Exception('workspace não encontrado');
        }
        return new Workspace($l['codigo']);
    }
}