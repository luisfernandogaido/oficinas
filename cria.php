<?php
use modelo\WhatsappValidacao;

include 'def.php';
$wv = WhatsappValidacao::cria($_GET['cod_usuario'], false);
d($wv);
