<?php

namespace modelo;

enum OsHistoricoCategoria: string
{
    case MUDANCA_STATUS = 'mudanca_status';
    case OPERACIONAL = 'operacional';
    case NOTA_INTERNA = 'nota_interna';
    case SISTEMA = 'sistema';
    case INTERACAO_CLIENTE = 'interacao_cliente';
}
