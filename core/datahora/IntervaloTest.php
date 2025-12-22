<?php
namespace datahora;

use PHPUnit\Framework\TestCase;

class IntervaloTest extends TestCase
{

    public function test()
    {
        $ini = \DateTime::createFromFormat('d/m/Y', '01/01/2015');
        $fim = \DateTime::createFromFormat('d/m/Y', '02/01/2020');
        $int = new Intervalo($ini, $fim);
        $periodos = $int->trimestres();
        $this->assertEquals(21, count($periodos));
    }
}
