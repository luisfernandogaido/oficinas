<?php

class Sistema
{
    public static $nome;
    public static $sigla;
    public static $versao;
    public static $ambiente;
    public static $hackCorreios;
    public static $app;
    public static $multiUsuarios;
    public static $temaProfinanc;
    public static bool $adminPersonifica;

    public static function ini()
    {
        $conf = conf('sistema');
        self::$nome = $conf['nome'];
        self::$sigla = $conf['sigla'];
        self::$versao = $conf['versao'];
        self::$ambiente = $conf['ambiente'];
        self::$app = $conf['app'];
        self::$multiUsuarios = $conf['multi_usuarios'];
        self::$temaProfinanc = $conf['tema_profinanc'];
        self::$adminPersonifica = $conf['admin_personifica'] ?? false;
        define('APP', $conf['app']);
    }
}
