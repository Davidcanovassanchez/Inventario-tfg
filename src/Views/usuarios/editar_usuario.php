<?php
require_once __DIR__ . '/../../config/sesion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: /tfg/Inventory-tfg/src/Views/auth/login.php");
    exit;
}
if (!isset($usuario)) {
    echo "<div class='alert alert-danger'>Usuario no encontrado</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>✏️ Editar usuario</h2>
    <form method="POST" action="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=editar&id=<?= $usuario['id_usuario'] ?>" class="card p-4">
        <div class="mb-3"><label>Nombre</label><input name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required></div>
        <div class="mb-3"><label>Nueva contraseña (opcional)</label><input type="password" name="password" class="form-control"></div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol_id" class="form-control">
                <option value="1" <?= $usuario['rol_id'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= $usuario['rol_id'] == 2 ? 'selected' : '' ?>>Trabajador</option>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <a href="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=index" class="btn btn-secondary">Volver</a>
            <button class="btn btn-primary" type="submit">Actualizar</button>
        </div>
    </form>
</div>
</body>
</html>
