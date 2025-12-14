<?php
require_once __DIR__ . '/../Models/Producto.php';
require_once __DIR__ . '/../Models/Movimiento.php';
require_once __DIR__ . '/../config/sesion.php';

$producto = new Producto();
$accion = $_GET['accion'] ?? 'listar';
$baseUrl = '/tfg/Inventory-tfg/src/Controllers/productoController.php';

$acciones_admin = ['crear', 'editar', 'eliminar'];
if (in_array($accion, $acciones_admin) && $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../Views/dashboard/trabajador.php");
    exit;
}

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $precio = $_POST['precio_unitario'];
            $stock = $_POST['stock'];

            if ($producto->existeNombre($nombre)) {
                $_SESSION['mensaje'] = "Ya existe un producto con el nombre <b>$nombre</b>.";
                header("Location: {$baseUrl}");
                exit;
            }

            if ($producto->crear($nombre, $descripcion, $precio, $stock)) {
                $_SESSION['mensaje'] = "Producto <b>$nombre</b> creado correctamente.";
            } else {
                $_SESSION['mensaje'] = "Error al crear el producto.";
            }

            header("Location: {$baseUrl}");
            exit;
        }
        include __DIR__ . '/../Views/productos/crear.php';
        break;

    case 'editar':
        if (!isset($_GET['id'])) {
            header("Location: {$baseUrl}");
            exit;
        }

        $id = $_GET['id'];
        $productoData = $producto->obtenerPorId($id);

        if (!$productoData) {
            $_SESSION['mensaje'] = "Producto no encontrado.";
            header("Location: {$baseUrl}");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $precio = $_POST['precio_unitario'];
            $stock = $_POST['stock'];

            if ($producto->actualizar($id, $nombre, $descripcion, $precio, $stock)) {
                $_SESSION['mensaje'] = "Producto <b>$nombre</b> actualizado correctamente.";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el producto.";
            }

            header("Location: {$baseUrl}");
            exit;
        }

        $producto = $productoData;
        include __DIR__ . '/../Views/productos/editar.php';
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            if ($producto->eliminar($_GET['id'])) {
                $_SESSION['mensaje'] = "Producto eliminado correctamente.";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar el producto.";
            }
        }
        header("Location: {$baseUrl}");
        exit;

    case 'actualizar_stock':
        if (!in_array($_SESSION['usuario']['rol'], ['admin', 'trabajador'])) {
            header("Location: ../Views/dashboard/trabajador.php");
            exit;
        }

        if (!isset($_GET['id'])) {
            $_SESSION['mensaje'] = "No se especificó un producto.";
            header("Location: {$baseUrl}");
            exit;
        }

        $id = (int)$_GET['id'];
        $productoData = $producto->obtenerPorId($id);

        if (!$productoData) {
            $_SESSION['mensaje'] = "Producto no encontrado.";
            header("Location: {$baseUrl}");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cantidad = (int)$_POST['cantidad'];
            $tipo = $_POST['tipo'];

            if ($tipo === 'salida') {
                $cantidad = -abs($cantidad);
            }

            $nuevoStock = $productoData['stock'] + $cantidad;

            if ($nuevoStock < 0) {
                $_SESSION['mensaje'] = "No hay suficiente stock.";
                header("Location: {$baseUrl}?accion=actualizar_stock&id={$id}");
                exit;
            }

            $producto->actualizarStock($id, $nuevoStock);

            $mov = new Movimiento();
            $mov->registrar(
                $id,
                abs($cantidad),
                $tipo,
                $_SESSION['usuario']['id']
            );

            $_SESSION['mensaje'] = "Stock actualizado correctamente.";
            header("Location: {$baseUrl}");
            exit;
        }

        $producto = $productoData;
        include __DIR__ . '/../Views/productos/actualizar_stock.php';
        break;

    case 'historial':
        $fechaInicio = $_GET['fecha_inicio'] ?? "";
        $fechaFin    = $_GET['fecha_fin'] ?? "";
        $productoF   = $_GET['producto'] ?? "";
        $usuarioF    = $_GET['usuario'] ?? "";
        $tipo        = $_GET['tipo'] ?? "";
        $cantidad    = $_GET['cantidad'] ?? "";

        $mov = new Movimiento();
        $movimientos = $mov->obtenerConFiltros(
            $fechaInicio,
            $fechaFin,
            $productoF,
            $usuarioF,
            $tipo,
            $cantidad
        );

        include __DIR__ . '/../Views/historial.php';
        break;

    default:
        $search   = $_GET['search']   ?? "";
        $orderBy  = $_GET['orderBy']  ?? "id_producto";
        $orderDir = $_GET['orderDir'] ?? "ASC";

        $productos = $producto->leerConFiltros($search, $orderBy, $orderDir);

        include __DIR__ . '/../Views/productos/listar.php';
        break;
}
?>
