<?php

namespace datahora;

use DateInterval;
use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use Exception;

use function explode;
use function floor;
use function intval;
use function is_string;
use function round;
use function str_replace;
use function substr;

class DataHora
{
    const MESES = [
        '1' => 'JANEIRO',
        '2' => 'FEVEREIRO',
        '3' => 'MARÇO',
        '4' => 'ABRIL',
        '5' => 'MAIO',
        '6' => 'JUNHO',
        '7' => 'JULHO',
        '8' => 'AGOSTO',
        '9' => 'SETEMBRO',
        '10' => 'OUTUBRO',
        '11' => 'NOVEMBRO',
        '12' => 'DEZEMBRO',
    ];

    /**
     * @param string $data
     * @return string
     * @throws Exception
     */
    public static function dataOuFimMes(string $data): string
    {
        $d = new DateTime($data);
        $dataFormatada = $d->format('Y-m-d');
        if ($data != $dataFormatada) {
            $d = new DateTime(substr($data, 0, 7) . '-01');
            $d->modify('last day of this month');
            return $d->format('Y-m-d');
        }
        return $data;
    }

    public static function diaExtenso(DateTime $d): string
    {
        $dia = $d->format('j');
        $mes = self::MESES[$d->format('n')];
        $ano = $d->format('Y');
        return "$dia de $mes de $ano";
    }

    /** Retorna um array de datas com distância de um mês entre si representando mensalidades.
     * Enquanto uma data cair num final de semana ou feriado, ela é avançada em um dia. A data subsequente
     * a uma data avançada terá, com isso, uma distância menor que um mês.
     * @param string $dataInicial Data no formato yyyy-mm-dd
     * @param int $meses
     * @return array
     * @throws Exception
     */
    public static function mensalidades(string $dataInicial, int $meses): array
    {
        $dataAnos = new DateTime($dataInicial);
        $anoInicial = $dataAnos->format('Y');
        $dataAnos->add(new DateInterval('P' . $meses . 'M'));
        $anoFinal = intval($dataAnos->format('Y')) + 1;
        $client = new CncClient();
        $f = $client->feriados($anoInicial, $anoFinal);
        $feriados = [];
        foreach ($f as $feriado) {
            $feriados[$feriado] = true;
        }
        $d = new DateTime($dataInicial);
        $mensalidades = [];
        for ($i = 0; $i < $meses; $i++) {
            $d2 = clone $d;
            $data = $d->format('Y-m-d');
            $diaSemana = $d->format('w');
            while ($diaSemana == '0' || $diaSemana == '6' || isset($feriados[$data])) {
                $d2->add(new DateInterval('P1D'));
                $data = $d2->format('Y-m-d');
                $diaSemana = $d2->format('w');
            }
            $mensalidades[] = $data;
            $d->add(new DateInterval('P1M'));
        }
        return $mensalidades;
    }

    /**
     * @param string $modo
     * @param string|null $data
     * @return string[]
     * @throws Exception
     */
    public static function iniFim(string $modo, ?string $data = null): array
    {
        $dia = new DateTime($data ?? null);
        switch ($modo) {
            case 'dia':
                $ini = $dia->format('Y-m-d') . ' 00:00:00';
                $fim = $dia->format('Y-m-d') . ' 23:59:59';
                break;
            case 'semana':
                if ($dia->format('w') != 0) {
                    $dia->modify('sunday this week')->modify('-7 day');
                }
                $ini = $dia->format('Y-m-d') . ' 00:00:00';
                $dia->modify('+6 day');
                $fim = $dia->format('Y-m-d') . ' 23:59:59';
                break;
            case 'mes':
                $dia->modify('first day of this month');
                $ini = $dia->format('Y-m-d') . ' 00:00:00';
                $dia->modify('last day of this month');
                $fim = $dia->format('Y-m-d') . ' 23:59:59';
                break;
            case 'ano':
                $ini = $dia->format('Y-01-01') . ' 00:00:00';
                $fim = $dia->format('Y-12-31') . ' 23:59:59';
                break;
            default:
                throw new Exception('modo inválido');
        }
        return ['ini' => $ini, 'fim' => $fim];
    }

    public static function horas(DateInterval $di): string
    {
        $days = intval($di->format('%a'));
        $hours = 24 * $days + $di->h;
        $minutos = $di->format('%I');
        if ($hours > 0) {
            return "{$hours}h{$minutos}m";
        }
        return "{$minutos}m";
    }

    public static function mesAno(DateTime $d)
    {
        $mes = self::MESES[$d->format('n')];
        $ano = $d->format('Y');
        return "$mes/$ano";
    }

    public static function diaBonito(string $isoDate): string
    {
        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        [$ano, $mes, $dia] = explode('-', substr($isoDate, 0, 10));
        $mes = $meses[intval($mes) - 1];
        return "$dia $mes $ano";
    }

    /**
     * @param DateTime $d
     * @return string
     * @throws Exception
     */
    public static function since(DateTime $d): string
    {
        $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $seconds = $now->getTimestamp() - $d->getTimestamp();
        if ($seconds < 60) {
            return 'agora';
        }
        if ($seconds < 3600) {
            $minutos = round($seconds / 60);
            if ($minutos == 1) {
                return 'há um minuto';
            }
            return "há $minutos minutos";
        }
        if ($seconds < 86400) {
            $horas = round($seconds / 3600);
            if ($horas == 1) {
                return 'há uma hora';
            }
            return "há $horas horas";
        }
        if ($seconds < 604800) {
            $dias = round($seconds / 86400);
            if ($dias == 1) {
                return 'há um dia';
            }
            return "há $dias dias";
        }
        if ($seconds < 31536000) {
            $semanas = round($seconds / 604800);
            if ($semanas == 1) {
                return 'há uma semana';
            }
            return "há $semanas semanas";
        }
        $anos = round($seconds / 31536000);
        if ($anos == 1) {
            return 'há um ano';
        }
        return "há $anos anos";
    }

    /**
     * @param DateTime $d
     * @return string
     * @throws Exception
     */
    public static function sinceShort(DateTime $d): string
    {
        $since = self::since($d);
        return str_replace(
            [
                'há',
                ' um minuto',
                ' minutos',
                ' uma hora',
                ' horas',
                ' um dia',
                ' dias',
                ' uma semana',
                ' semanas',
                ' um ano',
                ' anos',
            ],
            [
                '',
                '1m',
                'm',
                '1h',
                'h',
                '1d',
                'd',
                '1sem',
                'sem',
                '1a',
                'a',
            ],
            $since,
        );
    }

    /**
     * @param DateTime $d
     * @return string
     * @throws DateMalformedStringException
     */
    public static function sinceDays(DateTime $d): string
    {
        $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $seconds = $now->getTimestamp() - $d->getTimestamp();
        if ($seconds < 60) {
            return 'agora';
        }
        if ($seconds < 3600) {
            $minutos = floor($seconds / 60);
            if ($minutos == 1) {
                return 'há um minuto';
            }
            return "há $minutos minutos";
        }
        if ($seconds < 86400) {
            $horas = floor($seconds / 3600);
            if ($horas == 1) {
                return 'há uma hora';
            }
            return "há $horas horas";
        }
        $dias = floor($seconds / 86400);
        if ($dias == 1) {
            return 'há um dia';
        }
        return "há $dias dias";
    }

    /**
     * @param string|DateTime $data
     * @return string
     * @throws DateMalformedStringException
     */
    public static function human(string|DateTime $data): string
    {
        if (is_string($data)) {
            $data = new DateTime($data);
        }
        $agora = new DateTime;
        $diff = $agora->diff($data);
        if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i == 0) {
            return 'Agora';
        }
        if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0) {
            return "há $diff->i min";
        }
        if ($data->format('Y-m-d') === $agora->format('Y-m-d')) {
            return 'Hoje, ' . $data->format('H:i');
        }
        $ontem = (clone $agora)->modify('-1 day');
        if ($data->format('Y-m-d') === $ontem->format('Y-m-d')) {
            return 'Ontem, ' . $data->format('H:i');
        }
        if ($diff->days < 7) {
            $semana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            $diaSemana = $semana[(int)$data->format('w')];
            return "{$diaSemana}, " . $data->format('H:i');
        }
        if ($data->format('Y') === $agora->format('Y')) {
            $meses = [
                1 => 'Jan',
                2 => 'Fev',
                3 => 'Mar',
                4 => 'Abr',
                5 => 'Mai',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Ago',
                9 => 'Set',
                10 => 'Out',
                11 => 'Nov',
                12 => 'Dez'
            ];
            $mes = $meses[(int)$data->format('n')];
            return $data->format('d') . ' ' . $mes . ', ' . $data->format('H:i');
        }
        return $data->format('d/m/Y');
    }
}