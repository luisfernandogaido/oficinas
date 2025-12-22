<?php
namespace graficos;

use PHPUnit\Framework\TestCase;

class BarrasTemporaisTest extends TestCase
{

    public function test()
    {
        $ini = \DateTime::createFromFormat('d/m/Y H:i:s', '01/01/2015 00:00:00');
        $fim = \DateTime::createFromFormat('d/m/Y H:i:s', '31/12/2019 23:59:59');
        $bt = new BarrasTemporais($ini, $fim, 900);
        $fatias = $bt->renderiza();
        print_r($fatias);
    }
}
