<?php
namespace modelo;

use bd\My;
use Exception;

class Peso
{
    /**
     * @var int
     */
    private $codigo;

    /**
     * @var string
     */
    private $data;

    /**
     * @var float
     */
    private $peso;

    /**
     * Peso constructor.
     * @param int $codigo
     */
    public function __construct(?int $codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodigo(): int
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo(int $codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * @return float
     */
    public function getPeso(): float
    {
        return $this->peso;
    }

    /**
     * @param float $peso
     */
    public function setPeso(float $peso): void
    {
        $this->peso = $peso;
    }

    public function salva()
    {
        if (!$this->peso) {
            throw new Exception('peso obrigatório');
        }
        if ($this->codigo) {
            throw new Exception('update peso não implementado');
        }
        $c = My::con();
        $com = $c->prepare('INSERT INTO peso (peso, data) VALUES (?, NOW())');
        $com->bind_param('d', $this->peso);
        $com->execute();
        $this->codigo = $c->insert_id;
    }

    public function exclui()
    {
        if (!$this->codigo) {
            return;
        }
        $c = My::con();
        $com = $c->prepare('delete from peso where codigo = ?');
        $com->bind_param('i', $this->codigo);
        $com->execute();
    }

    public static function lista(): array
    {
        $c = My::con();
        $query = <<< SQL
            SELECT codigo, data, peso
            FROM peso
            ORDER BY codigo
        SQL;
        $r = $c->query($query);
        $pesos = [];
        while ($l = $r->fetch_assoc()) {
            $pesos[] = $l;
        }
        return $pesos;
    }
}