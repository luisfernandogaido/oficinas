<?php
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    Aut::personifica($_GET['codigo']);
    header('Location: ../index.php?sucesso=Personificado.');
} catch (Exception $e) {
    header('Location: index.php?erro=' . urlencode($e->getMessage()));
}