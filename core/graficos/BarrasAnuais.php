<?php

namespace graficos;

class BarrasAnuais

{
    private $dados = [];

    public function adiciona($mes, $ano, $valor, $dados = [])
    {
        $mes = (int)$mes;
        if ($mes < 1 || $mes > 12) {
            throw new \Exception('Mês precisa ser um número de 1 a 12.');
        }
        if (!isset($this->dados[$ano])) {
            $this->dados[$ano] = [];
        }
        if (!isset($this->dados[$ano][$mes])) {
            $this->dados[$ano][$mes] = [];
        }
        if ($valor < 0) {
            $valor = 0;
        }
        $dados['valor'] = $valor;
        $this->dados[$ano][$mes][] = $dados;
    }

    public function renderiza()
    {
        $retorno = [];
        foreach ($this->dados as $k => $v) {
            $ano = [
                'ano' => $k,
                'meses' => [],
            ];
            for ($i = 1; $i <= 12; $i++) {
                if (isset($v[$i])) {
                    $ano['meses'][] = $v[$i];
                } else {
                    $ano['meses'][] = [['valor' => 0]];
                }
            }
            $retorno[] = $ano;
        }
        return $retorno;
    }
}