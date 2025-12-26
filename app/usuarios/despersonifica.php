<?php
include '../../def.php';
try {
    Aut::despersonifica();
    header('Location: ../index.php');
} catch (Exception $e) {
    header('Location: index.php?erro=' . urlencode($e->getMessage()));
}