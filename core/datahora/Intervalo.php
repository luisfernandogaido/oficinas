<?php
namespace datahora;

use DateTime;
use DateInterval;
use Exception;

class Intervalo
{
    /**
     * @var DateTime
     */
    private $inicio;

    /**
     * @var DateTime
     */
    private $fim;

    /**
     * Intervalo constructor.
     * @param DateTime $inicio
     * @param DateTime $fim
     * @throws Exception Início maior que fim.
     */
    public function __construct(DateTime $inicio, DateTime $fim)
    {
        if ($inicio > $fim) {
            throw new Exception('Início não deve ser maior que fim.');
        }
        $this->inicio = $inicio;
        $this->fim = $fim;
    }

    public function dias()
    {
        $dias = [];
        $d = clone $this->inicio;
        while ($d <= $this->fim) {
            $dias[] = $d->format('Y-m-d');
            $d->add(new DateInterval('P1D'));
        }
        return $dias;
    }

    public function semanas()
    {
        $semanas = [];
        $d = clone $this->inicio;
        while ($d <= $this->fim) {
            $semanas[] = $d->format('W-Y');
            $d->add(new DateInterval('P1W'));
        }
        return $semanas;
    }

    public function meses()
    {
        $semanas = [];
        $d = clone $this->inicio;
        while ($d <= $this->fim) {
            $semanas[] = $d->format('m-y');
            $d->add(new DateInterval('P1M'));
        }
        return $semanas;
    }

    public function trimestres()
    {
        $trimestres = [];
        $d = clone $this->inicio;
        while ($d <= $this->fim) {
            $trimestre = str_pad(floor($d->format('m') / 3) + 1, 2, '0', STR_PAD_LEFT);
            $trimestres[] = $trimestre . '-' . $d->format('Y');
            $d->add(new DateInterval('P3M'));
        }
        return $trimestres;
    }

    public function anos()
    {
        $anos = [];
        $d = clone $this->inicio;
        while ($d <= $this->fim) {
            $anos[] = $d->format('Y');
            $d->add(new DateInterval('P1Y'));
        }
        return $anos;
    }
}