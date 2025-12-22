<?php
use modelo\WhatsappValidacao;

include '../../def.php';
try {
    $numero = $_GET['numero'] ?? 'numero';
    $codigo = $_GET['codigo'] ?? 'codigo';
    $nome = $_GET['nome'] ?? 'nome';
    $numero = str_replace('@c.us', '', $numero);
    $codigo = str_replace('Código de validação de WhatsApp: ', '', $codigo);
    if (str_starts_with($numero, '55')) {
        $numero = substr($numero, 2);
    }
    $resposta = WhatsappValidacao::solicitaValidacao($codigo, $numero);
    //language=html
    $corpo = <<< CORPO
<p>
    <b>Número:</b> {$numero}
</p>
<p>
    <b>Código:</b> {$codigo}
</p>
<p>
    <b>Nome:</b> {$nome}
</p>
<p>
    <b>Resposta:</b> {$resposta}
</p>
CORPO;
    $ret = ['resposta' => $resposta];
    notifyMe("solicitaValidacao: $resposta", $corpo);
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);