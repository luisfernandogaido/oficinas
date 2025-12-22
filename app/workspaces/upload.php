<?php
use app\client\Storage;
use modelo\Usuario;
use modelo\Workspace;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = $_POST['codigo'];
    $storage = new Storage();
    $files = $storage->uploadFromFiles(Sistema::$app, Aut::$codigo, "workspacelogo", $codigo, 0, 500, 30, 85);
    $file = $files[0] ?? null;
    if (!$file) {
        throw new Exception("Não foi possível subir o logo do workspace.");
    }
    $url = $file['url'] ?? null;
    $ws = new Workspace($codigo);
    if ($ws->logo) {
        try {
            $storage->fileDeleteByHash($ws->logo);
        } catch (Exception $e) {
        }
    }
    $ws->logo = $url;
    $ws->save();
    $ret = ['url' => $url];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);