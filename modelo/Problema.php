<?php
namespace modelo;

enum Problema: string
{
    case REVISAO_TROCA_OLEO = 'revisao_troca_oleo';
    case MOTOR_BARULHOS = 'motor_barulhos';
    case SUSPENSAO_PNEUS = 'suspensao_pneus';
    case FREIOS = 'freios';
    case ELETRICO_LUZES_PAINEL = 'eletrico_luzes_painel';
    case EMBREAGEM_CAMBIO = 'embreagem_cambio';
    case AR_CONDICIONADO = 'ar_condicionado';
    case SUPERAQUECIMENTO_VAZAMENTOS = 'superaquecimento_vazamentos';
    case FUNILARIA_PINTURA = 'funilaria_pintura';
    case OUTROS = 'outros';

    public function label(): string
    {
        return match ($this) {
            self::REVISAO_TROCA_OLEO => 'Revisão / Troca de Óleo',
            self::FREIOS => 'Freios',
            self::SUSPENSAO_PNEUS => 'Suspensão / Pneus',
            self::MOTOR_BARULHOS => 'Motor / Barulhos',
            self::SUPERAQUECIMENTO_VAZAMENTOS => 'Superaquecimento / Vazamentos',
            self::ELETRICO_LUZES_PAINEL => 'Elétrico / Luzes no Painel',
            self::EMBREAGEM_CAMBIO => 'Embreagem / Câmbio',
            self::AR_CONDICIONADO => 'Ar Condicionado',
            self::FUNILARIA_PINTURA => 'Funilaria / Pintura',
            self::OUTROS => 'Outros',
        };
    }
}
