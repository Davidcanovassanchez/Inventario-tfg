<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!function_exists('verificarRol')) {
    function verificarRol($rolRequerido) {
        if ($_SESSION['usuario']['rol'] != $rolRequerido) {
            header("Location: ../sin_permiso.php");
            exit;
        }
    }
}
?>
