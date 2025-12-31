<?php

namespace modelo;

enum OsStatus: string
{
    case RASCUNHO = 'rascunho';
    case PENDENTE_MODERACAO = 'pendente_moderacao';
    case BLOQUEADA = 'bloqueada';
    case SOLICITADA = 'solicitada';
    case ANALISE = 'analise';
    case AGENDADA = 'agendada';
    case AGUARDANDO_APROVACAO = 'aguardando_aprovacao';
    case EM_ANDAMENTO = 'em_andamento';
    case FINALIZADA = 'finalizada';
    case CONCLUIDA = 'concluida';
    case CANCELADA = 'cancelada';
    case REJEITADA = 'rejeitada';

    public function label(): string
    {
        return match ($this) {
            self::RASCUNHO => 'rascunho',
            self::PENDENTE_MODERACAO => 'pendente',
            self::BLOQUEADA => 'bloqueada',
            self::SOLICITADA => 'solicitada',
            self::ANALISE => 'análise',
            self::AGUARDANDO_APROVACAO => 'aguardando aprovação',
            self::AGENDADA => 'agendada',
            self::EM_ANDAMENTO => 'em andamento',
            self::CONCLUIDA => 'concluída',
            self::FINALIZADA => 'finalizada',
            self::CANCELADA => 'cancelada',
            self::REJEITADA => 'rejeitada',
        };
    }
}