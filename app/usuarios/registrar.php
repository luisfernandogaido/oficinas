<?php

use bd\Formatos;
use modelo\Usuario;

include '../../def.php';
if (!Aut::provisorio() && !Aut::$usuario->isIncompleto()) {
    header('Location: ../index.php');
}
$nome = Aut::$usuario->nomeReal();
$email = Aut::$usuario->emailReal();
$celular = Formatos::telefoneApp(Aut::$usuario->celular);
$cpfCnpj = Aut::$usuario->cpfCnpj;
$from = $_GET['from'] ?? null;
$whatsAppValidado = Aut::$usuario->whatsAppValidado;
$u = new Usuario(Aut::$codigo);
include "registrar.html.php";
