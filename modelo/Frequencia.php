<?php

namespace modelo;

enum Frequencia: string
{
    case SEMPRE = 'sempre';
    case AS_VEZES = 'as_vezes';
    case RARAMENTE = 'raramante';

    public function label(): string
    {
        return match ($this) {
            self::SEMPRE => 'Sempre',
            self::AS_VEZES => 'Às vezes',
            self::RARAMENTE => 'Raramante',
        };
    }
}
