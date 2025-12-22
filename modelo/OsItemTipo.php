<?php

namespace modelo;

enum OsItemTipo: string
{
    case PRODUTO = 'produto';
    case SERVICO = 'servico';

    public function label(): string
    {
        return match ($this) {
            self::PRODUTO => 'Produto',
            self::SERVICO => 'Serviço',
        };
    }
}