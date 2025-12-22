<?php
namespace excel;

use Exception;
use function intval;
use function pow;
use function strlen;
use function strpos;
use function strtoupper;
use function substr;
use function substr_replace;
use function trim;
use function upper;

class Excel
{
    const LETRAS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function columnToNumber(string $coluna): int
    {
        $array = [
            'A' => 0,
            'B' => 1,
            'C' => 2,
            'D' => 3,
            'E' => 4,
            'F' => 5,
            'G' => 6,
            'H' => 7,
            'I' => 8,
            'J' => 9,
            'K' => 10,
            'L' => 11,
            'M' => 12,
            'N' => 13,
            'O' => 14,
            'P' => 15,
            'Q' => 16,
            'R' => 17,
            'S' => 18,
            'T' => 19,
            'U' => 20,
            'V' => 21,
            'W' => 22,
            'X' => 23,
            'Y' => 24,
            'Z' => 25
        ];
        return $array[$coluna];
    }

    /**
     * @param string $col
     * @return int
     * @throws Exception
     */
    public static function colNum(string $col): int
    {
        $col = trim(strtoupper($col));
        $n = strlen($col);
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            $chr = $col[$i];
            $pos = strpos(self::LETRAS, $chr);
            if ($pos === false) {
                throw new Exception("excel colun: chr $chr não existente");
            }
            $pos++;
            $pow = $n - $i - 1;
            $ter = $pos * pow(26, $pow);
            $sum += $ter;
        }
        return $sum - 1;
    }

    public static function getCelula(array $excel, string $celula): ?string
    {
        $coluna = substr($celula, 0, 1);
        $coluna = upper(self::columnToNumber($coluna));
        $linha = intval(substr_replace($celula, '', 0, 1));
        $linha = $linha - 1;
        return $excel[$linha][$coluna];
    }

}