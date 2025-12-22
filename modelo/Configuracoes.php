<?php

namespace modelo;

use bd\My;
use Exception;

class Configuracoes
{
    public bool $whatsAppValidacaoAoCriar = false;
    public string $whatsApp = '14991623401';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $c = My::con();
        $query = <<< CONSTROI
            select whatsapp_validacao_ao_criar, whatsapp from configuracoes;
        CONSTROI;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        if (!$l) {
            return;
        }
        $this->whatsAppValidacaoAoCriar = $l['whatsapp_validacao_ao_criar'] == 1;
        $this->whatsApp = $l['whatsapp'];
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $c = My::con();
        $query = <<< SALVA
            replace into configuracoes (codigo, whatsapp_validacao_ao_criar, whatsapp) values (1, ?, ?);
        SALVA;
        $c->execute_query($query, [$this->whatsAppValidacaoAoCriar ? 1 : 0, $this->whatsApp]);
    }
}