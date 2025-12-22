<?php

namespace app\workspaces;

use modelo\Workspace;

class WorkspaceViewModel
{

    public function __construct(public Workspace $ws)
    {
    }

    public ?string $enderecoCompleto {
        get {
            if (!$this->ws->endereco) {
                return null;
            }
            $enderecoCompleto = "{$this->ws->endereco}, {$this->ws->numero}";
            if ($this->ws->complemento) {
                $enderecoCompleto .= ", {$this->ws->complemento}";
            }
            $enderecoCompleto .= ", {$this->ws->bairro}, {$this->ws->cidade}/{$this->ws->uf}, {$this->ws->cep}";
            return $enderecoCompleto;
        }
    }
}