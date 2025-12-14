<?php
session_start();

if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['usuario']['rol'];
    if ($rol === 'admin') {
        header("Location: Views/dashboard/admin.php");
        exit;
    } elseif ($rol === 'trabajador') {
        header("Location: Views/dashboard/trabajador.php");
        exit;
    }
}

header("Location: Views/auth/login.php");
exit;
?>
