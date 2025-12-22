<?php

namespace modelo;

use bd\Formatos;
use Exception;
use bd\My;

use function explode;
use function implode;

class Veiculo
{
    public int $codigo;
    public int $codProprietario;
    public VeiculoTipo $tipo;
    public string $marca;
    public string $modelo;
    public int $ano;
    public string $combustivel;
    public string $codigoFipe;
    public float $valorFipe;
    public string $idFipe; //não coloquei na tabela ainda
    public string $placa;
    public string $km;
    public string $criacao;
    public string $alteracao;
    public string $indice;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if (!$this->codigo) {
            return;
        }
        $c = My::con();
        $query = <<< CONSTROI
            select codigo,
                   cod_proprietario,
                   tipo,
                   marca,
                   modelo,
                   ano,
                   combustivel,
                   codigo_fipe,
                   valor_fipe,
                   id_fipe,
                   placa,
                   km,
                   criacao,
                   alteracao,
                   indice
            from veiculo where codigo = $codigo
        CONSTROI;
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new Exception('Veículo não encontrado.');
        }
        $this->codProprietario = $l['cod_proprietario'];
        $this->tipo = VeiculoTipo::from($l['tipo']);
        $this->marca = $l['marca'];
        $this->modelo = $l['modelo'];
        $this->ano = $l['ano'];
        $this->combustivel = $l['combustivel'];
        $this->codigoFipe = $l['codigo_fipe'];
        $this->valorFipe = $l['valor_fipe'];
        $this->idFipe = $l['id_fipe'];
        $this->placa = $l['placa'];
        $this->km = $l['km'];
        $this->criacao = $l['criacao'];
        $this->alteracao = $l['alteracao'];
        $this->indice = $l['indice'];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function salva(): void
    {
        if ($this->codigo) {
            $this->altera();
        } else {
            $this->insere();
        }
    }

    private function insere(): void
    {
        $c = My::con();
        $this->indice = implode(' ', [
            $this->tipo->value,
            $this->marca,
            $this->modelo,
            $this->ano,
            $this->combustivel,
            $this->codigoFipe,
            $this->placa,
        ]);
        $query = <<< INSERE
            insert into veiculo
            (
             cod_proprietario, tipo, marca, modelo, ano, combustivel, codigo_fipe,
             valor_fipe,id_fipe, placa, km, criacao, alteracao, indice
            )
            values
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now(), now(), ?)
        INSERE;
        $c->execute_query($query, [
            $this->codProprietario,
            $this->tipo->value,
            $this->marca,
            $this->modelo,
            $this->ano,
            $this->combustivel,
            $this->codigoFipe,
            $this->valorFipe,
            $this->idFipe,
            $this->placa,
            $this->km,
            $this->indice,
        ]);
        $this->codigo = $c->insert_id;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function altera(): void
    {
        $c = My::con();
        $c->query(
            "update veiculo set km = $this->km, cod_proprietario = $this->codProprietario where codigo = $this->codigo"
        );
    }

    public function modeloCurto(): string
    {
        $primeiraPalavraModelo = explode(' ', $this->modelo)[0] ?? $this->modelo;
        return "$this->marca $primeiraPalavraModelo $this->ano";
    }

    /**
     * @param int $codigo
     * @return Veiculo[]
     * @throws Exception
     */
    public static function doProprietario(int $codigo): array
    {
        $c = My::con();
        $query = <<< QUERY
            select codigo,
                   marca,
                   modelo,
                   ano,
                   combustivel,
                   codigo_fipe,
                   valor_fipe,
                   id_fipe,
                   placa,
                   km,
                   criacao,
                   alteracao,
                   indice
            from veiculo
            where cod_proprietario = $codigo
            order by codigo desc
        QUERY;
        $r = $c->query($query);
        $veiculos = [];
        while ($l = $r->fetch_assoc()) {
            $v = new Veiculo(0);
            $v->codigo = $l['codigo'];
            $v->codProprietario = $codigo;
            $v->marca = $l['marca'];
            $v->modelo = $l['modelo'];
            $v->ano = $l['ano'];
            $v->combustivel = $l['combustivel'];
            $v->codigoFipe = $l['codigo_fipe'];
            $v->valorFipe = $l['valor_fipe'];
            $v->idFipe = $l['id_fipe'];
            $v->placa = $l['placa'];
            $v->km = $l['km'];
            $v->criacao = $l['criacao'];
            $v->alteracao = $l['alteracao'];
            $v->indice = $l['indice'];
            $veiculos[] = $v;
        }
        return $veiculos;
    }
}