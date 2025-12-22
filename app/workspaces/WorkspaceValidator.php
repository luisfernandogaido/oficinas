<?php

namespace app\workspaces;

use Aut;
use Exception;
use bd\My;

class WorkspaceValidator
{
    /**
     * @param int $codWorkspace
     * @param int $codCriador
     * @return void
     * @throws Exception
     */
    public static function dono(int $codWorkspace, int $codCriador): void
    {
        $c = My::con();
        $query = "SELECT codigo from workspace where codigo = $codWorkspace and cod_criador = $codCriador";
        $l = $c->execute_query($query)->fetch_assoc();
        if (!$l) {
            throw new Exception('Workspace não pertence a este usuário');
        }
    }
}