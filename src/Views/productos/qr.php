<?php
require_once __DIR__ . '/../../config/sesion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo 'ID no especificado';
    exit;
}

$id = (int) $_GET['id'];

$qrData = "http://localhost/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=actualizar_stock&id=" . $id;

/*
$qrData = "http://192.168.0.21/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=actualizar_stock&id=" . $id;
// Para misma red
*/

$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>QR del Producto <?= $id ?></title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container text-center mt-5">
    <h2>📦 Código QR del Producto #<?= $id ?></h2>
    <p class="text-muted">Imprime o descarga este QR para usarlo en el inventario.</p>

    <div class="card p-4 shadow mx-auto" style="width: 350px;">
        <img
            src="<?= $qrUrl ?>"
            alt="Código QR"
            class="img-fluid mb-3"
        >

        <a
            href="<?= $qrUrl ?>"
            download="producto_<?= $id ?>.png"
            class="btn btn-primary"
        >
            ⬇ Descargar QR
        </a>

        <a
            href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=index"
            class="btn btn-secondary mt-3"
        >
            ⬅ Volver al listado
        </a>
    </div>
</div>

</body>
</html>
