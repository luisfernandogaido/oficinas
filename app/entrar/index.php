<?php
include '../../def.php';
$trocar = $_GET['trocar'] ?? null;
if (Aut::logado() && !$trocar) {
    header('Location: ../index.php');
}
$localUser = $_SERVER['LOCAL_USER'] ?? null;
$localPass = $_SERVER['LOCAL_PASS'] ?? null;
include "index.html.php";
