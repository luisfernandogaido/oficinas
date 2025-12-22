<?php
namespace modelo;

use bd\My;
use Exception;

class Projeto
{
    private int $codigo;
    private int $codUsuario;
    private int $codConta;
    private string $nome;

    /**
     * Projeto constructor.
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        if ($codigo) {
            $c = My::con();
            $query = <<< QUERY
                SELECT cod_usuario, cod_conta, nome
                FROM projeto
                WHERE codigo = $codigo            
            QUERY;
            $r = $c->query($query);
            $l = $r->fetch_assoc();
            if (!$l) {
                throw new Exception('projeto não encontrado');
            }
            $this->codUsuario = $l['cod_usuario'];
            $this->codConta = $l['cod_conta'];
            $this->nome = $l['nome'];
        }
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodigo(): int
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo(int $codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodUsuario(): int
    {
        return $this->codUsuario;
    }

    /**
     * @param int $codUsuario
     */
    public function setCodUsuario(int $codUsuario): void
    {
        $this->codUsuario = $codUsuario;
    }

    /**
     * @return int
     */
    public function getCodConta(): int
    {
        return $this->codConta;
    }

    /**
     * @param int $codConta
     */
    public function setCodConta(int $codConta): void
    {
        $this->codConta = $codConta;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @throws Exception
     */
    public function salva()
    {
        $c = My::con();
        if ($this->codigo) {
            $query = <<< QUERY
                UPDATE projeto SET
                nome = ?
                WHERE codigo = ?
            QUERY;
            $com = $c->prepare($query);
            $com->bind_param('si', $this->nome, $this->codigo);
            $com->execute();
        } else {
            $query = <<< QUERY
                INSERT INTO projeto
                (cod_usuario, cod_conta, nome)
                VALUES
                (?,?,?)
            QUERY;
            $com = $c->prepare($query);
            $com->bind_param('iis', $this->codUsuario, $this->codConta, $this->nome);
            $com->execute();
            $this->codigo = $c->insert_id;
        }
    }

    /**
     * @throws Exception
     */
    public function exclui()
    {
        $c = My::con();
        $c->query("DELETE FROM projeto WHERE codigo = $this->codigo");
    }

    public static function lista(int $codUsuario, bool $ordemRecentes): array
    {
        $c = My::con();
        $query = <<< QUERY
            SELECT codigo, nome
            FROM projeto
            WHERE cod_usuario = $codUsuario
            ORDER BY nome
        QUERY;
        if ($ordemRecentes) {
            $query = <<< QUERY
                SELECT p.codigo, p.nome, max(t.criacao) max_criacao
                FROM projeto p
                left join tarefa t on p.codigo = t.cod_projeto
                WHERE cod_usuario = $codUsuario
                group by p.codigo, p.nome
                ORDER BY max_criacao desc, p.nome;
            QUERY;
        }
        $r = $c->query($query);
        $projetos = [];
        while ($l = $r->fetch_assoc()) {
            $projetos[] = $l;
        }
        return $projetos;
    }
}