<?php 
if (!isset($_SESSION)) session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? "Inventario" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6fa; transition: all 0.3s ease; }
        #sidebar { width: 250px; height: 100vh; background: #212529; color: white; position: fixed; top: 0; left: 0; padding-top: 80px; transition: transform 0.3s ease; }
        #sidebar.hidden { transform: translateX(-260px); }
        #sidebar a { text-decoration: none; color: #ccc; padding: 12px 20px; display: block; }
        #sidebar a:hover { background: #0d6efd; color: white; }
        #topbar { height: 60px; background: #343a40; color: white; padding: 15px; margin-left: 250px; display: flex; align-items: center; justify-content: space-between; transition: margin-left 0.3s ease; }
        #topbar.expanded { margin-left: 0; }
        #content { margin-left: 260px; margin-top: 20px; transition: margin-left 0.3s ease; }
        #content.expanded { margin-left: 10px; }
        #toggleSidebar { font-size: 1.4rem; cursor: pointer; margin-right: 20px; }
    </style>
</head>
<body>
    <div id="topbar">
        <div class="d-flex align-items-center">
            <i id="toggleSidebar" class="bi bi-list"></i>
            <i class="bi bi-person-circle"></i>
            <strong class="ms-2"><?= $_SESSION['usuario']['nombre'] ?></strong>
            (<?= $_SESSION['usuario']['rol'] ?>)
        </div>
        <a href="/tfg/Inventory-tfg/src/Controllers/authController.php?accion=cerrar_sesion" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>

    <div id="sidebar">
        <h5 class="text-center mb-4">📦 INVENTARIO</h5>
        <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
            <a href="/tfg/Inventory-tfg/src/Views/dashboard/admin.php"><i class="bi bi-speedometer2"></i> Inicio</a>
            <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php"><i class="bi bi-boxes"></i> Productos</a>
            <a href="/tfg/Inventory-tfg/src/Controllers/userController.php?accion=index"><i class="bi bi-people"></i> Usuarios</a>
            <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=historial"><i class="bi bi-clock-history"></i> Historial</a>
        <?php else: ?>
            <a href="/tfg/Inventory-tfg/src/Views/dashboard/trabajador.php"><i class="bi bi-speedometer2"></i> Inicio</a>
            <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=listar"><i class="bi bi-box"></i> Ver productos</a>
            <a href="/tfg/Inventory-tfg/src/Controllers/productoController.php?accion=historial"><i class="bi bi-clock-history"></i> Historial</a>
        <?php endif; ?>
    </div>

    <div id="content">
        <?= $contenido ?>
    </div>

    <script>
        document.getElementById("toggleSidebar").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            const topbar = document.getElementById("topbar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("hidden");
            topbar.classList.toggle("expanded");
            content.classList.toggle("expanded");
        });
    </script>
</body>
</html>
