<?php
require_once __DIR__ . '/../config/sesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sin permiso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container text-center mt-5">
    <h1 class="text-danger">⛔ Acceso denegado</h1>
    <p class="lead">No tienes permisos para acceder a esta sección.</p>

    <a href="/tfg/Inventory-tfg/src/Views/dashboard/admin.php" class="btn btn-primary">
        Volver al panel
    </a>
</div>

</body>
</html>
