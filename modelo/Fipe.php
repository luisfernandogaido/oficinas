<?php
namespace modelo;

use app\client\FipeClient;
use bd\Formatos;
use Exception;
use bd\My;

class Fipe
{
    public string $id;
    public string $tipo;
    public string $marca;
    public string $modelo;
    public int $ano;
    public string $combustivel;
    public string $codigoFipe;
    public float $valor;
    public string $indice;

    public function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
            insert into fipe
                (id, tipo, marca, modelo, ano, combustivel, codigo_fipe, valor, indice)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        INSERE;
        $c->execute_query($query, [
            $this->id,
            $this->tipo,
            $this->marca,
            $this->modelo,
            $this->ano,
            $this->combustivel,
            $this->codigoFipe,
            $this->valor,
            $this->indice
        ]);
    }

    /**
     * @param string $ref
     * @return void
     * @throws Exception
     */
    public static function importa(string $ref): void
    {
        $c = My::con();
        $c->query('truncate fipe');
        $count = 0;
        foreach (new FipeClient()->refJsonl($ref) as $versao) {
            $v = new Fipe();
            $v->id = $versao['id'];
            $v->tipo = $versao['tipo'];
            $v->marca = $versao['marca'];
            $v->modelo = $versao['modelo'];
            $v->ano = $versao['ano'] != 32000 ? $versao['ano'] : 0;
            $v->combustivel = $versao['combustivel'];
            $v->codigoFipe = $versao['codigo_fipe'];
            $v->valor = $versao['valor'];
            $v->indice = $versao['indice'];
            $v->insere();
            $count++;
            echo "$count $v->indice\n";
        }
    }

    /**
     * @param string $search
     * @return Fipe[]
     */
    public static function find(string $search): array
    {
        $c = My::con();
        $search = Formatos::ft($search);
        $query = <<< FIND
            select id,
                   tipo,
                   id,
                   tipo,
                   marca,
                   modelo,
                   ano,
                   combustivel,
                   codigo_fipe,
                   valor,
                   indice
            from fipe
            where match(indice) against(? in boolean mode)
            order by ano desc
            limit 20
        FIND;
        $r = $c->execute_query($query, [$search]);
        $versoes = [];
        while ($l = $r->fetch_assoc()) {
            $v = new Fipe();
            $v->id = $l['id'];
            $v->tipo = $l['tipo'];
            $v->marca = $l['marca'];
            $v->modelo = $l['modelo'];
            $v->ano = $l['ano'];
            $v->combustivel = $l['combustivel'];
            $v->codigoFipe = $l['codigo_fipe'];
            $v->valor = $l['valor'];
            $v->indice = $l['indice'];
            $versoes[] = $v;
        }
        return $versoes;
    }
}