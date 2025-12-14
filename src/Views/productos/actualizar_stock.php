<?php
require_once __DIR__ . '/../../config/sesion.php';

if (!isset($producto)) {
    echo "❌ Error: No se ha recibido información del producto.";
    exit;
}

$volverUrl = $_SESSION['usuario']['rol'] === 'admin'
    ? '/tfg/Inventory-tfg/src/Views/dashboard/admin.php'
    : '/tfg/Inventory-tfg/src/Views/dashboard/trabajador.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="<?= $volverUrl ?>" class="navbar-brand">⬅ Volver al panel</a>
    </div>
</nav>

<div class="container mt-5">

    <h2 class="text-center mb-4">🔄 Actualizar Stock</h2>

    <div class="card shadow mx-auto" style="max-width: 500px;">
        <div class="card-body">

            <h4 class="text-center"><?= htmlspecialchars($producto['nombre']) ?></h4>
            <p class="text-muted text-center"><?= htmlspecialchars($producto['descripcion']) ?></p>

            <div class="alert alert-info text-center">
                <strong>Stock actual:</strong> <?= $producto['stock'] ?>
            </div>

            <form method="POST"
                  action="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=actualizar_stock&id=<?= $producto['id_producto'] ?>">

                <div class="mb-3">
                    <label class="form-label">Cantidad a modificar</label>
                    <input type="number" name="cantidad" class="form-control" required min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de movimiento</label>
                    <select name="tipo" class="form-select" required>
                        <option value="entrada">➕ Entrada</option>
                        <option value="salida">➖ Salida</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100">Guardar movimiento</button>
            </form>

        </div>
    </div>

</div>

</body>
</html>
