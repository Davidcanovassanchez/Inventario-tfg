<?php 
require __DIR__ . '/../config/sesion.php'; 
$rol = $_SESSION['usuario']['rol'] ?? 'trabajador';

$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin    = $_GET['fecha_fin'] ?? '';
$producto    = $_GET['producto'] ?? '';
$usuario     = $_GET['usuario'] ?? '';
$tipo        = $_GET['tipo'] ?? '';
$cantidad    = $_GET['cantidad'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📜 Historial de Movimientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .badge-entrada { background-color: #28a745; font-size: 0.9rem; }
        .badge-salida { background-color: #dc3545; font-size: 0.9rem; }
        .table-wrapper { background-color: #fff; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.1); }
        .btn-group-center { display: flex; justify-content: center; gap: 1rem; margin-top: 1.5rem; }
    </style>
</head>
<body>

<div class="container mt-5 table-wrapper">

    <h2 class="mb-4 text-center">📜 Historial de Movimientos</h2>

    <form method="get" action="/tfg/Inventory-tfg/src/Controllers/productoController.php" class="mb-4">
        <input type="hidden" name="accion" value="historial">

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-end">

                    <div class="col-md-2">
                        <label class="form-label small">Desde</label>
                        <input type="date" name="fecha_inicio"
                            value="<?= htmlspecialchars($fechaInicio) ?>"
                            class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Hasta</label>
                        <input type="date" name="fecha_fin"
                            value="<?= htmlspecialchars($fechaFin) ?>"
                            class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Producto</label>
                        <input type="text" name="producto"
                            value="<?= htmlspecialchars($producto) ?>"
                            class="form-control" placeholder="Producto">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Usuario</label>
                        <input type="text" name="usuario"
                            value="<?= htmlspecialchars($usuario) ?>"
                            class="form-control" placeholder="Usuario">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="entrada" <?= $tipo === 'entrada' ? 'selected' : '' ?>>Entrada</option>
                            <option value="salida" <?= $tipo === 'salida' ? 'selected' : '' ?>>Salida</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small">Cant.</label>
                        <input type="number" name="cantidad"
                            value="<?= htmlspecialchars($cantidad) ?>"
                            class="form-control" placeholder="#">
                    </div>

                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-success">Filtrar</button>
                    </div>

                    <div class="col-md-1 d-grid">
                        <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=historial"
                        class="btn btn-outline-secondary">Limpiar</a>
                    </div>

                </div>
            </div>
        </div>
    </form>


    <?php if (empty($movimientos)): ?>
        <div class="alert alert-info text-center">
            No hay movimientos que coincidan con los filtros.
        </div>
    <?php else: ?>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $m): ?>
                        <tr>
                            <td><?= date("d/m/Y H:i", strtotime($m['fecha'])) ?></td>
                            <td><?= htmlspecialchars($m['nombre_producto']) ?></td>
                            <td><?= htmlspecialchars($m['nombre_usuario'] . ' ' . $m['apellido_usuario']) ?></td>
                            <td>
                                <?php if ($m['tipo'] === 'entrada'): ?>
                                    <span class="badge badge-entrada">Entrada</span>
                                <?php else: ?>
                                    <span class="badge badge-salida">Salida</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $m['tipo'] === 'salida' ? '-' . abs($m['cantidad']) : $m['cantidad'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="btn-group-center">
        <?php if ($rol === 'admin'): ?>
            <a href="/tfg/Inventory-tfg/src/Views/dashboard/admin.php" class="btn btn-secondary btn-lg">⬅ Volver al panel administrador</a>
        <?php else: ?>
            <a href="/tfg/Inventory-tfg/src/Views/dashboard/trabajador.php" class="btn btn-secondary btn-lg">⬅ Volver al panel trabajador</a>
        <?php endif; ?>
        <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=listar" class="btn btn-primary btn-lg">📦 Ir a productos</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
