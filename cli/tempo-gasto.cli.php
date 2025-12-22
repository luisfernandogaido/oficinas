<?php
use modelo\Tarefa;

include 'def.cli.php';

$codProjetoConsorcio = 10;
$codProjetoChatbot = 31;

$codProjeto = $codProjetoConsorcio;
//$codProjeto = $codProjetoChatbot;

$ini = '2022-09-01 00:00:00';
$fim = '2023-05-17 23:59:59';
$tempos = Tarefa::tempoGastoProjeto($codProjeto, $ini, $fim);
dd($tempos);
$f = fopen('tempo-gasto.csv', 'w');
fputcsv($f, [
    'cod_tarefa',
    'nome_tarefa',
    'tempo',
    'cards',
]);
foreach ($tempos as $tempo) {
    fputcsv($f, [
        $tempo['cod_tarefa'],
        $tempo['tarefa'],
        $tempo['tempo'],
        implode(', ', $tempo['cards']),
    ]);
}
fclose($f);

