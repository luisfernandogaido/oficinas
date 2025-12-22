<?php

namespace modelo;

use datahora\DataHora;
use DateTime;
use DateTimeZone;
use Exception;
use bd\My;
use function array_reverse;
use function now;

class Registro
{
    const MAX_SECONDS_DELETE = 1800;

    public int $codigo;
    public string $date;
    public string $type;
    public ?string $args = null;
    public ?string $text = null;
    public ?float $float = null;
    public string $created;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        if ($codigo) {
            $c = My::con();
            $query = <<< CONSTROI
                SELECT `date`, `type`, `args`, `text`, `float`, created
                FROM registro
                WHERE codigo = $codigo            
            CONSTROI;
            $r = $c->query($query);
            $l = $r->fetch_assoc();
            if (!$l) {
                throw new Exception('registro não encontrado');
            }
            $this->date = $l['date'];
            $this->type = $l['type'];
            $this->args = $l['args'];
            $this->text = $l['text'];
            $this->float = $l['float'];
            $this->created = $l['created'];
        }
        $this->codigo = $codigo;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function save(): void
    {
        $c = My::con();
        if ($this->codigo) {
            throw new Exception('update não implementado');
            return;
        }
        $query = <<< UPDATE
            INSERT INTO registro
            (`date`, `type`, `args`, `text`, `float`, created)
            VALUES
            (?, ?, ?, ?, ?, now())            
        UPDATE;
        $com = $c->prepare($query);
        $com->execute([$this->date, $this->type, $this->args, $this->text, $this->float]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function delete(): void
    {
        $now = now();
        $created = new DateTime($this->created, new DateTimeZone('America/Sao_Paulo'));
        $seconds = $now->getTimestamp() - $created->getTimestamp();
        if ($seconds > self::MAX_SECONDS_DELETE) {
            throw new Exception('Erro ao excluir registros: MAX_SECONDS_DELETE superado.');
        }
        $c = My::con();
        $c->query("DELETE FROM registro WHERE codigo = $this->codigo");
    }

    /**
     * @param bool $porDia
     * @return array
     * @throws Exception
     */
    public static function aguas(bool $porDia): array
    {
        $c = My::con();
        if ($porDia) {
            $query = <<< AGUAS1
                SELECT DATE(`date`) dia, SUM(`float`) ml
                FROM registro
                WHERE `type` = 'agua'
                GROUP BY dia
                ORDER BY dia DESC
                LIMIT 100
            AGUAS1;
            $r = $c->query($query);
            $aguas = [];
            while ($l = $r->fetch_assoc()) {
                $aguas[] = $l;
            }
            return $aguas;
        }
        $query = <<< AGUAS2
            SELECT codigo, `date`, `float`
            FROM registro
            WHERE `type` = 'agua'
            ORDER BY codigo DESC
            LIMIT 100
        AGUAS2;
        $r = $c->query($query);
        $aguas = [];
        while ($l = $r->fetch_assoc()) {
            $aguas[] = $l;
        }
        return $aguas;
    }

    /**
     * @param string $modo
     * @return array
     * @throws Exception
     */
    public static function hls(string $modo): array
    {
        return match ($modo) {
            'fluxo' => self::hlsFluxo(),
            'por-dia' => self::hlsPorDia(),
            'por-mes' => self::hlsPorMes(),
        };
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function hlsFluxo(): array
    {
        $c = My::con();
        $query = <<< HLS_FLUXO
            SELECT codigo, `date`, `args`
            FROM registro
            WHERE TYPE = 'hl'
            ORDER BY `date`
        HLS_FLUXO;
        $r = $c->query($query);
        $hls = [];
        $previousDate = null;
        while ($l = $r->fetch_assoc()) {
            $date = new DateTime($l['date'], new DateTimeZone('America/Sao_Paulo'));
            if ($previousDate) {
                $di = $previousDate->diff($date);
                $l['horas'] = DataHora::horas($di);
            } else {
                $l['horas'] = null;
            }
            $previousDate = $date;
            $hls[] = $l;
        }
        return array_reverse($hls);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function hlsPordia(): array
    {
        $c = My::con();
        $query = <<< HLS_POR_DIA
            SELECT DATE(`date`) dia, COUNT(*) f
            FROM registro
            WHERE TYPE = 'hl'
            GROUP BY dia
            ORDER BY dia DESC
        HLS_POR_DIA;
        $r = $c->query($query);
        $hls = [];
        while ($l = $r->fetch_assoc()) {
            $hls[] = $l;
        }
        return $hls;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function hlsPorMes(): array
    {
        $c = My::con();
        $query = <<< HLS_POR_DIA
            SELECT YEAR(`date`) ano, MONTH(`date`) mes, COUNT(*) f
            FROM registro
            WHERE TYPE = 'hl'
            GROUP BY ano, mes
            ORDER BY ano DESC, mes DESC
        HLS_POR_DIA;
        $r = $c->query($query);
        $hls = [];
        while ($l = $r->fetch_assoc()) {
            $hls[] = $l;
        }
        return $hls;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function barbas(): array
    {
        $c = My::con();
        $query = <<< BARBAS
            SELECT codigo, `date`, `text`
            FROM registro
            WHERE TYPE = 'barba'
            ORDER BY `date` desc
            LIMIT 100
        BARBAS;
        $r = $c->query($query);
        $barbas = [];
        $previousDate = null;
        while ($l = $r->fetch_assoc()) {
            $date = new DateTime($l['date'], new DateTimeZone('America/Sao_Paulo'));
            if ($previousDate) {
                $di = $previousDate->diff($date);
                $l['horas'] = DataHora::horas($di);
            } else {
                $l['horas'] = null;
            }
            $previousDate = $date;
            $barbas[] = $l;
        }
        return $barbas;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function cabelos(): array
    {
        $c = My::con();
        $query = <<< BARBAS
            SELECT codigo, `date`, `text`
            FROM registro
            WHERE TYPE = 'cabelo'
            ORDER BY `date`
            LIMIT 100
        BARBAS;
        $r = $c->query($query);
        $cabelos = [];
        $previousDate = null;
        while ($l = $r->fetch_assoc()) {
            $date = new DateTime($l['date'], new DateTimeZone('America/Sao_Paulo'));
            if ($previousDate) {
                $di = $previousDate->diff($date);
                $l['horas'] = DataHora::horas($di);
            } else {
                $l['horas'] = null;
            }
            $previousDate = $date;
            $cabelos[] = $l;
        }
        return array_reverse($cabelos);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function outros(): array
    {
        $c = My::con();
        $query = <<< BARBAS
            SELECT codigo, `date`, `text`
            FROM registro
            WHERE TYPE = 'outros'
            ORDER BY `date`
            LIMIT 100
        BARBAS;
        $r = $c->query($query);
        $outros = [];
        $previousDate = null;
        while ($l = $r->fetch_assoc()) {
            $date = new DateTime($l['date'], new DateTimeZone('America/Sao_Paulo'));
            if ($previousDate) {
                $di = $previousDate->diff($date);
                $l['horas'] = DataHora::horas($di);
            } else {
                $l['horas'] = null;
            }
            $previousDate = $date;
            $outros[] = $l;
        }
        return array_reverse($outros);
    }
}