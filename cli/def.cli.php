<?php
error_reporting(E_ALL);
define('RAIZ', str_replace('\\', '/', dirname(__DIR__)) . '/');
include RAIZ . 'core/funcoes.php';
spl_autoload_register('autoload');
define('SERVIDOR', gethostname());
cli();
Sistema::ini();