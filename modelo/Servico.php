<?php

namespace modelo;

use bd\Formatos;
use bd\My;
use DateTime;
use Exception;
use UnexpectedValueException;

use function array_filter;
use function file_get_contents;
use function implode;
use function json_decode;
use function number_format;

class Servico
{
    public int $codigo;
    public int $codCategoria;
    public string $nome;
    public string $descricao;
    public bool $interno = false;
    public int $contadorUso = 0;
    public float $preco;
    public float $custo;
    public int $tempo;
    public bool $ativo = true;
    public string $criacao;
    public string $alteracao;
    public string $indice;

    /**
     * @param int $codigo
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if (!$this->codigo) {
            return;
        }
        $c = My::con();
        $query = <<< SQL
            select cod_categoria,
                   nome,
                   descricao,
                   interno,
                   contador_uso,
                   preco,
                   custo,
                   tempo,
                   ativo,
                   criacao,
                   alteracao,
                   indice
            from servicos
            where codigo = $codigo
        SQL;
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new UnexpectedValueException('Serviço não encontrado.');
        }
        $this->codCategoria = $l['cod_categoria'];
        $this->nome = $l['nome'];
        $this->descricao = $l['descricao'];
        $this->interno = $l['interno'] == 1;
        $this->contadorUso = $l['contador_uso'];
        $this->preco = $l['preco'];
        $this->custo = $l['custo'];
        $this->tempo = $l['tempo'];
        $this->ativo = $l['ativo'] == 1;
        $this->criacao = $l['criacao'];
        $this->alteracao = $l['alteracao'];
        $this->indice = $l['indice'];
    }

    /**
     * @return void
     */
    public function save(): void
    {
        if ($this->codigo) {
            $this->altera();
        } else {
            $this->insere();
        }
    }

    /**
     * @return void
     */
    private function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
        insert into servicos
            (cod_categoria, nome, descricao, interno, contador_uso, preco, custo, tempo, criacao, alteracao, indice)
        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
        INSERE;
        $com = $c->prepare($query);
        $com->execute([
            $this->codCategoria,
            $this->nome,
            $this->descricao,
            $this->interno ? 1 : 0,
            $this->contadorUso,
            $this->preco,
            $this->custo,
            $this->tempo,
            $this->criacao,
            $this->alteracao,
            $this->indice
        ]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return void
     */
    private function altera(): void
    {
        $c = My::con();
        $query = <<< ALTERA
        update servicos
        set interno      = ?,
            contador_uso = ?,
            preco        = ?
        where codigo = ?
        ALTERA;
        $c->execute_query($query, [$this->interno ? 1 : 0, $this->contadorUso, $this->preco, $this->codigo]);
    }

    /**
     * @param int $codWorkspace
     * @return void
     * @throws Exception
     */
    public static function generate(int $codWorkspace): void
    {
        CategoriaServico::generate($codWorkspace);
        $categorias = CategoriaServico::list($codWorkspace);
        $servicos = json_decode(file_get_contents('https://gaido.space/data/servicos.json'), true);
        foreach ($categorias as $categoria) {
            $servicosCategoria = array_filter($servicos, fn($servico) => $categoria['nome'] == $servico['categoria']);
            foreach ($servicosCategoria as $servicoCategoria) {
                $servico = new Servico(0);
                $servico->codCategoria = $categoria['codigo'];
                $servico->nome = $servicoCategoria['nome'];
                $servico->descricao = $servicoCategoria['descricao'];
                $servico->preco = $servicoCategoria['preco_sugerido'];
                $servico->custo = $servicoCategoria['custo_medio'];
                $servico->tempo = $servicoCategoria['tempo_estimado_minutos'];
                $servico->criacao = new DateTime()->format('Y-m-d H:i:s');
                $servico->alteracao = $servico->criacao;
                $servico->indice = implode(' ', [
                    $servico->nome,
                    $categoria['nome'],
                ]);
                $servico->save();
            }
        }
    }

    public static function search(int $codWorkspace, string $search): array
    {
        $search = Formatos::ft($search);
        $c = My::con();
        $where = ["c.cod_workspace = ?"];
        $param = [$codWorkspace];
        if ($search) {
            $where[] = "match(s.indice) against(? in boolean mode)";
            $param[] = $search;
        } else {
            $where[] = "s.interno = 1";
        }
        $strWhere = implode(" and ", $where);
        $query = <<< SQL
            select s.codigo, s.nome, s.descricao, s.preco, c.nome categoria, s.interno, s.contador_uso,
                   s.tempo
            from servicos s
                 inner join categorias_servicos c on s.cod_categoria = c.codigo
            where $strWhere
            order by c.nome, s.nome
        SQL;
        $r = $c->execute_query($query, $param);
        $produtos = [];
        while ($l = $r->fetch_assoc()) {
            $produtos[] = $l;
        }
        return $produtos;
    }
}