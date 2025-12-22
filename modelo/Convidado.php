<?php
namespace modelo;

use Exception;
use bd\My;

class Convidado
{
    public int $codigo;
    public int $codConvite;
    public string $entrada;

    /**
     * @param int $codigo
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
            insert into convidado (codigo, cod_convite, entrada)
            values (?, ?, now())
        INSERE;
        $com = $c->prepare($query);
        $com->execute([$this->codigo, $this->codConvite]);
    }
}