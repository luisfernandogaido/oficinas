<?php
namespace modelo;

enum UnidadeMedida: string
{
    case UNIDADE = 'un';
    case JOGO = 'jg';
    case PACOTE = 'pc';
    case LITRO = 'lt';
    case METRO = 'mt';
    case QUILOGRAMA = 'kg';
}
