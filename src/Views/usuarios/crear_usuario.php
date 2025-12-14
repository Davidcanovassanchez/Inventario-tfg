<?php
require_once __DIR__ . '/../../config/sesion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: /tfg/Inventory-tfg/src/Views/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Crear usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>➕ Nuevo usuario</h2>
    <form method="POST" action="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=crear" class="card p-4">
        <div class="mb-3"><label>Nombre</label><input name="nombre" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol_id" class="form-control">
                <option value="1">Administrador</option>
                <option value="2">Trabajador</option>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <a href="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=index" class="btn btn-secondary">Volver</a>
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
</div>
</body>
</html>
