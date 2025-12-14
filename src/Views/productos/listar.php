<?php
require_once __DIR__ . '/../../config/sesion.php';
require_once __DIR__ . '/../../Models/Producto.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}

$rol = $_SESSION['usuario']['rol'] ?? 'invitado';

$search       = $_GET['search'] ?? "";
$orderBy      = $_GET['orderBy'] ?? "fecha_creacion";
$orderDir     = $_GET['orderDir'] ?? "DESC";
$fechaInicio  = $_GET['fecha_inicio'] ?? "";
$fechaFin     = $_GET['fecha_fin'] ?? "";

$producto = new Producto();
$productos = $producto->leerConFiltros($search, $orderBy, $orderDir, $fechaInicio, $fechaFin);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📦 Listado de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .card .form-label {
            margin-bottom: 0.2rem;
        }
    </style>
</head>

<body class="bg-light">
<div class="container mt-4">

    <?php if (isset($_SESSION['mensaje'])):
        $mensaje = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        $clase = 'info';
        if (str_contains($mensaje, '✅')) $clase = 'success';
        elseif (str_contains($mensaje, '✏️')) $clase = 'primary';
        elseif (str_contains($mensaje, '🗑️')) $clase = 'danger';
        elseif (str_contains($mensaje, '⚠️')) $clase = 'warning';
    ?>
        <div class="alert alert-<?= $clase ?> text-center alert-dismissible fade show">
            <?= $mensaje ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3 align-items-center">
    <h1 class="mb-0">📦 Listado de Productos</h1>
    <?php if ($rol === 'admin'): ?>
        <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=crear"
           class="btn btn-success d-flex align-items-center justify-content-center">
            ➕ Añadir producto
        </a>
    <?php endif; ?>
    </div>

    <form method="get" class="mb-4" id="filtroForm">
        <input type="hidden" name="accion" value="listar">
        <input type="hidden" name="orderBy" id="orderBy" value="<?= $orderBy ?>">
        <input type="hidden" name="orderDir" id="orderDir" value="<?= $orderDir ?>">

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label small">Buscar</label>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                               class="form-control" placeholder="Nombre del producto">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Ordenar por</label>
                        <select id="ordenSelect" class="form-select">
                            <option value="precio_unitario" <?= $orderBy === 'precio_unitario' ? 'selected' : '' ?>>Precio</option>
                            <option value="stock" <?= $orderBy === 'stock' ? 'selected' : '' ?>>Stock</option>
                            <option value="fecha_creacion" <?= $orderBy === 'fecha_creacion' ? 'selected' : '' ?>>Fecha</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Dirección</label>
                        <button type="button" id="dirButton" class="btn btn-primary w-100"></button>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Desde</label>
                        <input type="date" name="fecha_inicio" value="<?= $fechaInicio ?>" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Hasta</label>
                        <input type="date" name="fecha_fin" value="<?= $fechaFin ?>" class="form-control">
                    </div>

                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-success">Filtrar</button>
                    </div>

                    <div class="col-md-1 d-grid">
                        <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=listar"
                           class="btn btn-outline-secondary">Limpiar</a>
                    </div>

                </div>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped shadow-sm align-middle">
        <thead class="table-dark text-center">
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Stock</th>
            <th>Precio (€)</th>
            <th>Fecha creación</th>
            <th>Acciones</th>
        </tr>
        </thead>

        <tbody>
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($p['stock']) ?></td>
                    <td class="text-end"><?= number_format($p['precio_unitario'], 2) ?></td>
                    <td><?= date('Y-m-d H:i:s', strtotime($p['fecha_creacion'])) ?></td>

                    <td class="text-center">
                        <?php if ($rol === 'admin'): ?>
                            <div class="btn-group btn-group-sm" role="group">
                                <a class="btn btn-warning" title="Editar producto"
                                   href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=editar&id=<?= $p['id_producto'] ?>">✏️</a>
                                <a class="btn btn-danger" title="Eliminar producto"
                                   onclick="return confirm('¿Eliminar producto?')"
                                   href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=eliminar&id=<?= $p['id_producto'] ?>">🗑️</a>
                                <a class="btn btn-info" title="Ver código QR"
                                   href="/tfg/Inventory-tfg/src/Views/productos/qr.php?id=<?= $p['id_producto'] ?>">📄</a>
                            </div>
                            <?php else: ?>
                                <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=actualizar_stock&id=<?= $p['id_producto'] ?>"
                                class="btn btn-sm btn-primary">Actualizar stock</a>
                            <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">No hay productos registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-3">
        <?php if ($rol === 'admin'): ?>
            <a href="/tfg/Inventory-tfg/src/Views/dashboard/admin.php" class="btn btn-outline-dark">🏠 Volver al panel</a>
        <?php else: ?>
            <a href="/tfg/Inventory-tfg/src/Views/dashboard/trabajador.php" class="btn btn-outline-dark">🏠 Volver al panel</a>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const orderByInput = document.getElementById('orderBy');
const orderDirInput = document.getElementById('orderDir');
const dirButton = document.getElementById('dirButton');
const ordenSelect = document.getElementById('ordenSelect');

function actualizarBoton() {
    const orderBy = ordenSelect.value;
    orderByInput.value = orderBy;

    if (orderBy === 'fecha_creacion') {
        dirButton.innerText = orderDirInput.value === 'DESC' ? 'Más reciente' : 'Más antiguo';
    } else {
        dirButton.innerText = orderDirInput.value === 'ASC' ? 'Ascendente' : 'Descendente';
    }
}

ordenSelect.addEventListener('change', () => {
    const orderBy = ordenSelect.value;
    orderDirInput.value = (orderBy === 'fecha_creacion') ? 'DESC' : 'ASC';
    actualizarBoton();
});

dirButton.addEventListener('click', () => {
    orderDirInput.value = (orderDirInput.value === 'ASC') ? 'DESC' : 'ASC';
    document.getElementById('filtroForm').submit();
});

actualizarBoton();
</script>
</body>
</html>
