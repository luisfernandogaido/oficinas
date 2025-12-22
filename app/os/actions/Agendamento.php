<?php

namespace app\os\actions;

use DateMalformedStringException;
use DateTime;

use function count;
use function d;

/**
 * Aplicando A Lei de Hick (Redução de Carga Cognitiva), Agendamento não apresenta calendários com complexas opções
 * para quem precisa apenas informar data e hora simples num futuro próximo. Ao invés disso, apresenta divisões
 * claras entre data e hora, para campos separados na tela.
 * Dias têm rótulos como Hoje, Amanhã, Sex, Sáb, Seg, Ter.
 * Horários têm valores das 08h00 às 18h00, step 30 minutos. *
 */
class Agendamento
{
    const INI_EXP = "08:00:00";
    const FIM_EXP = "20:00:00";
    const DIAS = 6;
    const DIAS_SEMANA = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

    /**
     * @return array
     * @throws DateMalformedStringException
     */
    public static function dias(): array
    {
        $agora = new DateTime();
        $hoje = $agora->format("Y-m-d");
        $finalExpedienteHoje = new DateTime("$hoje " . self::FIM_EXP);
        $data = clone $agora;
        $dias = [];
        while (count($dias) < self::DIAS) {
            if ($data->format('Y-m-d') == $hoje && $data > $finalExpedienteHoje) {
                $data->modify("+1 day");
                continue;
            };
            if ($data->format("w") == 0) {
                $data->modify("+1 day");
                continue;
            }
            $days = $agora->diff($data, true)->days;
            $rotulo = match ($days) {
                0 => 'Hoje',
                1 => 'Amanhã',
                default => self::DIAS_SEMANA[intval($data->format('w'))],
            };
            $dias[] = [
                'data' => $data->format("Y-m-d"),
                'rotulo' => $rotulo,
                'e_hoje' => $rotulo == 'Hoje',
            ];
            $data->modify("+1 day");
        }
        return $dias;
    }

    /**
     * @return array
     * @throws DateMalformedStringException
     */
    public static function horarios(): array
    {
        $agora = new DateTime();
        $data = new DateTime(self::INI_EXP);
        $fina = new DateTime(self::FIM_EXP);
        $horarios = [];
        while ($data <= $fina) {
            $horarios[] = [
                'horario' => $data->format('H:i'),
                'disponivel_se_hoje' => $data > $agora,
            ];
            $data->modify("+30 minutes");
        }
        return $horarios;
    }
}