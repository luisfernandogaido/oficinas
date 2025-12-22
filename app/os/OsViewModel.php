<?php

namespace app\os;

use Aut;
use DateMalformedStringException;
use DateTime;
use modelo\Os;
use modelo\OsStatus;

class OsViewModel
{
    /**
     * @param Os $os
     */
    public function __construct(public Os $os)
    {
    }

    public ?string $previsaoEntrada {
        /**
         * @return string|null
         * @throws DateMalformedStringException
         */
        get {
            if (!$this->os->agendamento) {
                return null;
            }
            $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            $agendamento = new DateTime($this->os->agendamento);
            return $diasSemana[$agendamento->format('w')] . $agendamento->format(', d/m à\s H:i');
        }
    }

    public ?string $previsaoEntrega {
        /**
         * @return string|null
         * @throws DateMalformedStringException
         */
        get {
            if (!$this->os->previsaoEntrega) {
                return null;
            }
            $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            $previsaoEntrega = new DateTime($this->os->previsaoEntrega);
            return $diasSemana[$previsaoEntrega->format('w')] . $previsaoEntrega->format(', d/m à\s H:i');
        }
    }

    public bool $podeEditarProblema {
        get {
            if (Aut::isGaido()) {
                return true;
            }
            return match ($this->os->status) {
                OsStatus::RASCUNHO, OsStatus::AGENDADA, OsStatus::SOLICITADA, OsStatus::PENDENTE_MODERACAO => true,
                default => false,
            };
        }
    }

    public bool $podeAgir = false;

    public bool $temEstimativa {
        get {
            if ($this->os->valor == 0.0) {
                return false;
            }
            return match ($this->os->status) {
                OsStatus::SOLICITADA, OsStatus::AGENDADA, OsStatus::ANALISE => true,
                default => false,
            };
        }
    }

    public bool $temOrcamento {
        get {
            if ($this->os->valor == 0.0) {
                return false;
            }
            return match ($this->os->status) {
                OsStatus::AGUARDANDO_APROVACAO,
                OsStatus::EM_ANDAMENTO,
                OsStatus::FINALIZADA,
                OsStatus::CONCLUIDA => true,
                default => false,
            };
        }
    }

    public string $criacaoH {
        /**
         * @return string
         * @throws DateMalformedStringException
         */
        get => new DateTime($this->os->criacao)->format('d/m/Y H:i');
    }

    public bool $podeReabrirFinalizada {
        get {
            if ($this->os->status != OsStatus::FINALIZADA) {
                return false;
            }
            //em algum momento do futuro, vou definir um prazo/idade da OS. Afinal, meu filho, não dá pra ficar
            //"revirando o passado".

            //defina também em Os::reabre a mesma regra.

            return true;
        }
    }

    public bool $buttonHome = false;

}