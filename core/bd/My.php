<?php
namespace bd;

use mysqli;
use Sistema;
use function mysqli_init;
use function strtoupper;
use const MYSQLI_OPT_INT_AND_FLOAT_NATIVE;

class My
{
    const ERR_DUPLICIDADE = 1062;
    const ERR_CHAVE_ESTRANGEIRA = 1451;
    private static array $conexoes = [];

    public static function con(?string $app = null): mysqli
    {
        if (!$app) {
            $app = strtoupper(Sistema::$app);
        }
        if (isset(self::$conexoes[$app])) {
            return self::$conexoes[$app];
        }
        $host = $_SERVER["{$app}_MYSQL_HOST"] ?? null;
        $user = $_SERVER["{$app}_MYSQL_USER"] ?? null;
        $pass = $_SERVER["{$app}_MYSQL_PASS"] ?? null;
        $base = $_SERVER["{$app}_MYSQL_BASE"] ?? null;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = mysqli_init();
        $mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
        $mysqli->real_connect($host, $user, $pass, $base);
        $mysqli->set_charset('utf8mb4');
        self::$conexoes[$app] = $mysqli;
        return self::$conexoes[$app];
    }
}