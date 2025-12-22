<?php
namespace graficos;

use PHPUnit\Framework\TestCase;

class BarrasPeriodoTest extends TestCase
{

    public function test()
    {
        $inicio = \DateTime::createFromFormat('d/m/Y', '01/01/2015');
        $fim = \DateTime::createFromFormat('d/m/Y', '31/12/2019');
        $bp = new BarrasPeriodo($inicio, $fim, BarrasPeriodo::PERIODO_MENSAL);
        $dados = $bp->gera();
        print_r($dados);
    }
}
