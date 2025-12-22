<?php
use modelo\Workspace;
use templates\Gaido;

include '../../def.php';
try {
    $ws = Workspace::porHash($_GET['h']);
    $endereco = null;
    if ($ws->descricao && $ws->numero && $ws->bairro && $ws->cidade && $ws->uf) {
        $endereco = "$ws->endereco, $ws->numero \n $ws->bairro, $ws->cidade/$ws->uf";
    }
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}