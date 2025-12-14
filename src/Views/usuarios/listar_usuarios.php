<?php
require_once __DIR__ . '/../../Config/sesion.php';
require_once __DIR__ . '/../../Models/Usuario.php';

verificarRol('admin');

$usuarioModel = new Usuario();

$searchId    = $_GET['id'] ?? "";
$searchName  = $_GET['nombre'] ?? "";
$searchEmail = $_GET['email'] ?? "";
$searchRol   = $_GET['rol'] ?? "";

$orderBy  = $_GET['orderBy'] ?? "id_usuario";
$orderDir = $_GET['orderDir'] ?? "ASC";

$usuarios = $usuarioModel->leerConFiltros($searchId, $searchName, $searchEmail, $searchRol, $orderBy, $orderDir);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">

    <?php if (!empty($_SESSION['mensaje'])): ?>
        <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
            <?= htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h1 class="text-center mb-4">👥 Gestión de Usuarios</h1>

    <div class="text-end mb-3">
        <a href="/tfg/Inventory-tfg/src/Views/usuarios/crear_usuario.php" class="btn btn-success">➕ Añadir usuario</a>
    </div>

    <form method="get" class="mb-4">
        <input type="hidden" name="accion" value="listar">

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-end">

                    <div class="col-md-1">
                        <label class="form-label small">ID</label>
                        <input type="number" name="id"
                            value="<?= htmlspecialchars($searchId) ?>"
                            class="form-control" placeholder="ID">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Nombre</label>
                        <input type="text" name="nombre"
                            value="<?= htmlspecialchars($searchName) ?>"
                            class="form-control" placeholder="Nombre">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Email</label>
                        <input type="email" name="email"
                            value="<?= htmlspecialchars($searchEmail) ?>"
                            class="form-control" placeholder="Email">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Rol</label>
                        <select name="rol" class="form-select">
                            <option value="">Todos los roles</option>
                            <option value="admin" <?= $searchRol === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="trabajador" <?= $searchRol === 'trabajador' ? 'selected' : '' ?>>Trabajador</option>
                        </select>
                    </div>

                    <div class="col-md-1 d-grid">
                        <button class="btn btn-success">Filtrar</button>
                    </div>

                    <div class="col-md-1 d-grid">
                        <a href="/tfg/Inventory-tfg/src/Views/usuarios/listar_usuarios.php"
                        class="btn btn-outline-secondary">Limpiar</a>
                    </div>

                </div>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped shadow-sm align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['id_usuario']) ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= $u['rol_id'] == 1 ? 'Admin' : 'Trabajador' ?></td>
                        <td>
                            <a href="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=editar&id=<?= $u['id_usuario'] ?>" 
                               class="btn btn-warning btn-sm me-1">✏️ Editar</a>

                            <a href="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=eliminar&id=<?= $u['id_usuario'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No hay usuarios registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-4 text-center">
        <a href="/tfg/Inventory-tfg/src/Views/dashboard/admin.php" class="btn btn-outline-dark">🏠 Volver al panel administrador</a>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
