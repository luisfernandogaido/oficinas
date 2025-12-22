<?php
use math\Bytes;
use modelo\Os;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['h'];
    $os = Os::porHash($hash);
    $totalSize = 0;
    $totalOriginalSize = 0;
    $files = $os->filesProblema();
    foreach ($files as $file) {
        $totalSize += $file['size'];
        $totalOriginalSize += $file['compression']['originalSize'];
    }
    $totalSizeH = new Bytes($totalSize)->formata();
    $totalOriginalSizeH = new Bytes($totalOriginalSize)->formata();
    $souEu = Aut::$codigo == 1 || Aut::$codPersonificador == 1;
    include 'passo5-load.html.php';
} catch (Throwable $e) {
    error_log($e);
    echo '<div class="error">' . e($e->getMessage()) . '</div>';
}