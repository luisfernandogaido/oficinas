<?php
include '../../def.php';
try {
    Aut::despersonifica();
    header('Location: ../index.php?sucesso=Despersonificado.');
} catch (Exception $e) {
    header('Location: index.php?erro=' . urlencode($e->getMessage()));
}