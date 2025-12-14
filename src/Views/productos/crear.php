<?php
require_once __DIR__ . '/../../config/sesion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../auth/login.php"); 
    exit;
}

$rol = $_SESSION['usuario']['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h1 class="mb-4 text-center">Nuevo Producto</h1>

    <form method="POST" action="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=crear" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Precio (€)</label>
                <input type="number" name="precio_unitario" step="0.01" min="0" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" min="0" class="form-control" required>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-primary">Guardar producto</button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="/tfg/Inventory-tfg/src/Views/dashboard/admin.php" class="btn btn-outline-dark">Volver al panel administrador</a>
    </div>
</div>
</body>
</html>
