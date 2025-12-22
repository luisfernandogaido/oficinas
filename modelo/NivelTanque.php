<?php

namespace modelo;

enum NivelTanque: int
{
    case RESERVA = 0;
    case UM_QUARTO = 25;
    case MEIO = 50;
    case TRES_QUARTOS = 75;
    case CHEIO = 100;

    public function label(): string
    {
        return match ($this) {
            self::RESERVA => 'Reserva',
            self::UM_QUARTO => '1/4',
            self::MEIO => '1/2',
            self::TRES_QUARTOS => '3/4',
            self::CHEIO => 'Cheio',
        };
    }
}