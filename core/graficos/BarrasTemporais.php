<?php

namespace graficos;

class BarrasTemporais
{
    /**
     * @var \DateTime Intervalo inicial
     */
    private $inicio;

    /**
     * @var \DateTime Intervalo final
     */
    private $fim;

    /**
     * @var int Tempo em segundos de cada fatia. Exemplo: de 15 em 15 minutos, seria 900.
     */
    private $intervalo;

    /**
     * @var array datas e valores agrupados.
     */
    private $dados;

    /**
     * BarrasTemporais constructor.
     * @param \DateTime $inicio
     * @param \DateTime $fim
     * @param int $intervalo
     */
    public function __construct(\DateTime $inicio, \DateTime $fim, $intervalo)
    {
        $this->inicio = $inicio;
        $this->fim = $fim;
        $this->intervalo = $intervalo;
    }

    public function adiciona($fatia, $valor)
    {
        $this->dados[$fatia] = $valor;
    }

    public function renderiza()
    {
        $p = clone $this->inicio;
        $ret = [];
        while ($p <= $this->fim) {
            $fatia = $p->format('Y-m-d H:i:s');
            if (isset($this->dados[$fatia])) {
                $valor = $this->dados[$fatia];
            } else {
                $valor = 0;
            }
            $ret[] = ['data' => $fatia, 'valor' => $valor];
            $p->modify('+' . $this->intervalo . ' second');
        }
        return $ret;
    }
}