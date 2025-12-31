<?php

namespace app\workspaces;

use Aut;
use bd\My;
use Exception;
use modelo\Usuario;

class WorkspaceValidator
{
    /**
     * @param int $codWorkspace
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public static function dono(int $codWorkspace, int $codUsuario): void
    {
        if (Aut::$perfil == Usuario::PERFIL_MASTER || new Usuario($codUsuario)->perfil == Usuario::PERFIL_MASTER) {
            return;
        }
        $c = My::con();
        $query = "SELECT codigo from workspace where codigo = $codWorkspace and cod_criador = $codUsuario";
        $l = $c->execute_query($query)->fetch_assoc();
        if (!$l) {
            throw new Exception('Workspace não pertence a este usuário');
        }
    }
}