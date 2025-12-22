<?php
error_reporting(E_ALL);
define('RAIZ', str_replace('\\', '/', __DIR__) . '/');
include RAIZ . 'core/funcoes.php';
spl_autoload_register('autoload');
define('SERVIDOR', str_replace('www.', '', $_SERVER['HTTP_HOST']));
define('SITE', $_SERVER['REQUEST_SCHEME'] . '://' . str_replace('//', '/',
        $_SERVER['HTTP_HOST'] . '/' . str_replace([$_SERVER['DOCUMENT_ROOT'], '/'], '', RAIZ) . '/'));
Sistema::ini();
session_name(APP);
session_start();
Aut::ini();
header('X-Session-ID: ' . session_id());
header('X-User-ID: ' . Aut::$codigo);
header('X-User-ID2: ' . Aut::$codPersonificador);

$aquiFora = 'eu vivo aqui';

$a = function () use ($aquiFora) {
    return $aquiFora;
};

$b = fn() => $aquiFora;