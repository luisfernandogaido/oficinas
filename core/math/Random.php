<?php
namespace math;

class Random
{
    public static function alfaNum($n)
    {
        $ret = '';
        $caracteres = 'abcdefghijklmnopqrstuvwxyz1234567890';
        for ($i = 0; $i < $n; $i++) {
            $ret .= $caracteres[random_int(0, 35)];
        }
        return $ret;
    }

    public static function num($n)
    {
        $ret = '';
        $caracteres = '1234567890';
        for ($i = 0; $i < $n; $i++) {
            $ret .= $caracteres[random_int(0, 9)];
        }
        return $ret;
    }
}