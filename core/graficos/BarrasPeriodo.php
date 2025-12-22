<?php
namespace graficos;

use datahora\Intervalo;

class BarrasPeriodo
{
    const PERIODO_DIARIO = 'diario';
    const PERIODO_SEMANAL = 'semanal';
    const PERIODO_MENSAL = 'mensal';
    const PERIODO_TRIMESTRAL = 'trimestral';
    const PERIODO_ANUAL = 'anual';
    const PERIODOS = [
        'diario',
        'semanal',
        'mensal',
        'trimestral',
        'anual',
    ];

    /**
     * @var \DateTime
     */
    private $inicio;

    /**
     * @var \DateTime
     */
    private $fim;

    /**
     * @var int
     */
    private $periodo;

    /**
     * @var
     */
    private $dados;

    /**
     * BarrasPeriodo constructor.
     * @param \DateTime $inicio
     * @param \DateTime $fim
     * @param int $periodo
     * @throws \Exception Período inválido.
     */
    public function __construct(\DateTime $inicio, \DateTime $fim, $periodo)
    {
        if (!in_array($periodo, self::PERIODOS)) {
            throw new \Exception('Período inválido.');
        }
        $this->inicio = $inicio;
        $this->fim = $fim;
        $this->periodo = $periodo;
    }

    public function gera()
    {
        $int = new Intervalo($this->inicio, $this->fim);
        switch ($this->periodo) {
            case self::PERIODO_DIARIO:
                $dados = $int->dias();
                break;
            case self::PERIODO_SEMANAL:
                $dados = $int->semanas();
                break;
            case self::PERIODO_MENSAL:
                $dados = $int->meses();
                break;
            case self::PERIODO_TRIMESTRAL:
                $dados = $int->trimestres();
                break;
            case self::PERIODO_ANUAL:
                $dados = $int->anos();
                break;
        }
        return $dados;
    }

}