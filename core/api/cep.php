<?php
include '../../def.php';
$url = 'https://viacep.com.br/ws/' . $_GET['cep'] . '/json/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url);
$res = curl_exec($ch);
header('Content-type: application/json; charset=utf-8');
echo $res;

