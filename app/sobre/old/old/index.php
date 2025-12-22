<?php
include '../../def.php';
try {
    include 'index.html.php';
} catch (\Exception $e) {
    \templates\Elite::erro($e);
}