<?php
use modelo\WhatsappValidacao;

include 'def.cli.php';

//$resposta = WhatsappValidacao::solicitaValidacao('YN23DM', '19994972303'); //5058
$resposta = WhatsappValidacao::solicitaValidacao('7ZPK15', '14981199946');
echo "$resposta\n";