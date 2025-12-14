<?php
require_once __DIR__ . '/../../Config/sesion.php';
require_once __DIR__ . '/../../Models/Producto.php';
require_once __DIR__ . '/../../Models/Usuario.php';
require_once __DIR__ . '/../../Models/Movimiento.php';
verificarRol('admin');

$productoModel = new Producto();
$usuarioModel = new Usuario();
$movimientoModel = new Movimiento();

$totalProductos = $productoModel->contarTodos();
$totalUsuarios = $usuarioModel->contarTodos();
$ultimosMovimientos = $movimientoModel->obtenerUltimos(5);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { overflow-x: hidden; }
        #sidebar { position: fixed; top: 0; left: -250px; width: 250px; height: 100%; background: #343a40; color: white; transition: 0.3s; padding-top: 60px; z-index: 1000; }
        #sidebar.active { left: 0; }
        #sidebar a { color: white; display: block; padding: 15px 20px; text-decoration: none; }
        #sidebar a:hover { background: #495057; }
        #overlay { position: fixed; display: none; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 900; }
        #overlay.active { display: block; }
        #toggle-btn { position: fixed; top: 15px; left: 15px; z-index: 1100; }
        #main-content { display: flex; flex-direction: column; align-items: center; justify-content: flex-start; min-height: 100vh; padding: 60px 20px 20px 20px; }
        .card-clickable { cursor: pointer; transition: transform 0.2s; }
        .card-clickable:hover { transform: scale(1.05); }
        @media (max-width: 768px) { #sidebar { width: 200px; } #toggle-btn { top: 10px; left: 10px; } }
    </style>
</head>
<body class="bg-light">

    <button id="toggle-btn" class="btn btn-dark">☰ Menú</button>

    <div id="sidebar">
        <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php">📦 Gestionar productos</a>
        <a href="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=index">👥 Gestionar usuarios</a>
        <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=historial">📜 Historial de Movimientos</a>
        <a href="/tfg/Inventory-tfg/src/Controllers/authController.php?accion=cerrar_sesion">🔒 Cerrar sesión</a>
    </div>

    <div id="overlay"></div>

    <div id="main-content" class="container">

        <h1 class="text-center mb-4">Panel de Administración</h1>
        <p class="lead text-center">Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></strong> (<?= htmlspecialchars($_SESSION['usuario']['rol']) ?>)</p>

        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
            <div class="card card-clickable text-white bg-primary" style="width: 18rem;" onclick="window.location='/tfg/Inventory-tfg/src/Controllers/productoController.php';">
                <div class="card-body text-center">
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text display-6"><?= $totalProductos ?></p>
                </div>
            </div>

            <div class="card card-clickable text-white bg-secondary" style="width: 18rem;" onclick="window.location='/tfg/Inventory-tfg/src/Controllers/userController.php?accion=index';">
                <div class="card-body text-center">
                    <h5 class="card-title">Usuarios</h5>
                    <p class="card-text display-6"><?= $totalUsuarios ?></p>
                </div>
            </div>

            <div class="card card-clickable text-white bg-info" style="width: 18rem;" onclick="window.location='/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=historial';">
                <div class="card-body text-center">
                    <h5 class="card-title">Historial</h5>
                    <p class="card-text display-6"><?= count($ultimosMovimientos) ?></p>
                </div>
            </div>

            <div class="card card-clickable text-white bg-success" style="width: 18rem;" onclick="window.location='/tfg/Inventory-tfg/src/Views/productos/escanear_qr.php?rol=admin';">
                <div class="card-body text-center">
                    <h5 class="card-title">Actualizar Stock (QR)</h5>
                    <p class="card-text display-6">🔄</p>
                </div>
            </div>
        </div>

        <div class="mt-5 w-100" style="max-width:800px;">
            <h3 class="mb-3 text-center">Últimos Movimientos</h3>
            <?php if (!empty($ultimosMovimientos)): ?>
                <table class="table table-striped table-hover shadow">
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
                        <?php foreach ($ultimosMovimientos as $m): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i:s', strtotime($m['fecha'])) ?></td>
                                <td><?= htmlspecialchars($m['nombre_producto']) ?></td>
                                <td><?= htmlspecialchars($m['nombre_usuario'] . ' ' . $m['apellido_usuario']) ?></td>
                                <td>
                                    <?php if ($m['tipo'] === 'entrada'): ?>
                                        <span class="badge bg-success">Entrada</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Salida</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $m['cantidad'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center">No hay movimientos recientes.</div>
            <?php endif; ?>
        </div>

    </div>

<script>
const toggleBtn = document.getElementById('toggle-btn');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});
</script>

</body>
</html>
