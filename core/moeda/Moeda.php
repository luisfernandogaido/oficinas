<?php
namespace moeda;

use Exception;
use function number_format;

class Moeda
{
    /**
     * @param float $num
     * @return string
     * @throws Exception
     */
    public static function valorPorExtenso(float $num): string
    {
        $strnum = number_format($num, 2, '.', '');
        $partes = explode('.', $strnum);
        $reais = $partes[0];
        $centavos = $partes[1] ?? 0;
        $milhares = [];
        $con = 0;
        while (strlen($reais) > 0) {
            $milhar = substr($reais, -3);
            $reais = substr($reais, 0, strlen($reais) - strlen($milhar));
            $trinca = floatval($milhar);
            switch ($con) {
                case 0:
                    $milhares[] = self::trinca($trinca);
                    break;
                case 1:
                    $sufixo = 'mil';
                    $milhares[] = self::trinca($trinca) . ' ' . $sufixo;
                    break;
                case 2:
                    $sufixo = $milhar > 1 ? 'milhões' : 'milhão';
                    $milhares[] = self::trinca($trinca) . ' ' . $sufixo;
                    break;
                case 3:
                    $sufixo = $milhar > 1 ? 'bilhões' : 'bilhão';
                    $milhares[] = self::trinca($trinca) . ' ' . $sufixo;
                    break;
                case 4:
                    $sufixo = $milhar > 1 ? 'trilhões' : 'trilhão';
                    $milhares[] = self::trinca($trinca) . ' ' . $sufixo;
                    break;
                default:
                    throw new Exception('número muito grande');

            }
            $con++;
        }
        $parteReais = implode(' e ', array_reverse($milhares));
        $parteReais .= $num >= 2 ? ' reais' : ' real';
        $parteCentavos = '';
        if ($centavos) {
            $parteCentavos = ' e ' . self::trinca($centavos);
            $parteCentavos .= $centavos >= 2 ? ' centavos' : ' centavo';
        }
        $parteReais = str_replace(['e  reais'], ['reais'], $parteReais);
        return $parteReais . $parteCentavos;
    }

    private static function trinca(float $num): string
    {
        $pequenos = [
            0 => '',
            1 => 'um',
            2 => 'dois',
            3 => 'três',
            4 => 'quatro',
            5 => 'cinco',
            6 => 'seis',
            7 => 'sete',
            8 => 'oito',
            9 => 'nove',
            10 => 'dez',
            11 => 'onze',
            12 => 'doze',
            13 => 'treze',
            14 => 'quatorze',
            15 => 'quinze',
            16 => 'dezesseis',
            17 => 'dezessete',
            18 => 'dezoito',
            19 => 'dezenove',
        ];

        $dezenas = [
            2 => 'vinte',
            3 => 'trinta',
            4 => 'quarenta',
            5 => 'cinquenta',
            6 => 'sessenta',
            7 => 'setenta',
            8 => 'oitenta',
            9 => 'noventa',
        ];

        $centenas = [
            1 => 'cento',
            2 => 'duzentos',
            3 => 'trezentos',
            4 => 'quatrocentos',
            5 => 'quinhentos',
            6 => 'seicentos',
            7 => 'setecentos',
            8 => 'oitocentos',
            9 => 'novecentos',
        ];

        $menorQueCem = function (float $num) use ($pequenos, $dezenas): string {
            if ($num < 20) {
                return $pequenos[$num];
            }
            $quociente = intdiv($num, 10);
            $resto = $num % 10;
            if ($resto) {
                return $dezenas[$quociente] . ' e ' . $pequenos[$resto];
            }
            return $dezenas[$quociente];
        };
        if ($num < 100) {
            return $menorQueCem($num);
        }
        if ($num < 101) {
            return 'cem';
        }
        $quociente = intdiv($num, 100);
        $resto = $num % 100;
        if ($resto) {
            return $centenas[substr($quociente, 0, 1)] . ' e ' . $menorQueCem(substr($resto, -2));
        }
        return $centenas[substr($quociente, 0, 1)];
    }
}