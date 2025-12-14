<?php
require_once __DIR__ . '/../../config/sesion.php';

$rol = $_SESSION['usuario']['rol'];

$volverUrl = $rol === 'admin'
    ? '/tfg/Inventory-tfg/src/Views/dashboard/admin.php'
    : '/tfg/Inventory-tfg/src/Views/dashboard/trabajador.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Escanear QR del Producto</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
        }
        h2 { margin-bottom: 30px; }
        #reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border: 4px solid #343a40;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
        }
        .instructions {
            text-align: center;
            margin-top: 20px;
            font-size: 1.1rem;
            color: #495057;
        }
        .btn-back { margin-top: 30px; }
    </style>
</head>
<body>

<h2>📷 Escanear código QR del producto</h2>

<div id="reader"></div>

<p class="instructions">
    Coloca el código QR del producto frente a la cámara.<br>
    La aplicación detectará automáticamente el código.
</p>

<a href="<?= $volverUrl ?>" class="btn btn-secondary btn-lg btn-back">
    ⬅ Volver al panel
</a>

<script>
function onScanSuccess(decodedText) {
    window.location.href = decodedText;
}

function onScanFailure(error) {
    console.warn('Error de escaneo:', error);
}

const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    { fps: 10, qrbox: 250, aspectRatio: 1.0 },
    false
);

html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>

</body>
</html>
