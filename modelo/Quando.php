<?php
namespace modelo;

enum Quando: string
{
    case HOJE = 'hoje';
    case ONTEM = 'ontem';
    case NESTA_SEMANA = 'nesta_semana';
    case NESTE_MES = 'neste_mes';
    case HA_MESES = 'ha_meses';
    case HA_ANOS = 'ha_anos';

    public function label(): string
    {
        return match ($this) {
            self::HOJE => 'Hoje',
            self::ONTEM => 'Ontem',
            self::NESTA_SEMANA => 'Nesta semana',
            self::NESTE_MES => 'Neste mês',
            self::HA_MESES => 'Há meses',
            self::HA_ANOS => 'Há anos',
        };
    }
}
