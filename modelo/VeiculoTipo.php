<?php

namespace modelo;

enum VeiculoTipo: string
{
    case CARRO = 'carro';
    case MOTO = 'moto';
    case CAMINHAO = 'caminhao';

    public function labels(): string
    {
        return match ($this) {
            self::CARRO => 'carro',
            self::MOTO => 'moto',
            self::CAMINHAO => 'caminhao',
        };
    }
}
