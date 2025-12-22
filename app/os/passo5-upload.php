<?php
use app\client\Storage;
use math\Bytes;
use modelo\Os;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_POST['hash'];
    $os = Os::porHash($hash);

    $totalOriginalSize = 0;
    $files = $os->filesProblema();
    foreach ($files as $file) {
        $totalOriginalSize += $file['compression']['originalSize'];
    }
    foreach ($_FILES['arquivo']['size'] as $size) {
        $totalOriginalSize += $size;
    }
    if ($totalOriginalSize > Os::MAX_TOTAL_ORIGINAL_SIZE_PROBLEMA) {
        $maxTotalOriginalSizeProblema = new Bytes(Os::MAX_TOTAL_ORIGINAL_SIZE_PROBLEMA)->formata();
        throw new Exception("Espaço usado para reportar problema da OS não pode exceder $maxTotalOriginalSizeProblema");
    }
    $storage = new Storage();
    $files = $storage->uploadFromFiles(
        Sistema::$app,
        Aut::$codigo,
        'os_problema',
        $hash,
        0,
        1080,
        30,
        85,
    );
    $ret = ['files' => $files];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);