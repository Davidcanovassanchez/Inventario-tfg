<?php
require_once __DIR__ . '/../config/db.php';

class Movimiento {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function registrar($id_producto, $cantidad, $tipo, $id_usuario) {
        $sql = "INSERT INTO inventario.movimientos 
                (id_producto, cantidad, tipo, id_usuario, fecha)
                VALUES (:id_producto, :cantidad, :tipo, :id_usuario, NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id_producto' => $id_producto,
            ':cantidad' => $cantidad,
            ':tipo' => $tipo,
            ':id_usuario' => $id_usuario
        ]);
    }

    public function obtenerTodos() {
        $sql = "
            SELECT 
                m.fecha,
                m.cantidad,
                m.tipo,
                p.nombre AS nombre_producto,
                u.nombre AS nombre_usuario,
                u.apellido AS apellido_usuario
            FROM inventario.movimientos m
            JOIN inventario.producto p ON p.id_producto = m.id_producto
            JOIN inventario.usuario u ON u.id_usuario = m.id_usuario
            ORDER BY m.fecha DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($movimientos as &$mov) {
            if ($mov['tipo'] === 'salida' && $mov['cantidad'] > 0) {
                $mov['cantidad'] = -$mov['cantidad'];
            }
        }

        return $movimientos;
    }

    public function obtenerUltimos($limite = 5) {
        $sql = "
            SELECT 
                m.fecha,
                m.cantidad,
                m.tipo,
                p.nombre AS nombre_producto,
                u.nombre AS nombre_usuario,
                u.apellido AS apellido_usuario
            FROM inventario.movimientos m
            JOIN inventario.producto p ON p.id_producto = m.id_producto
            JOIN inventario.usuario u ON u.id_usuario = m.id_usuario
            ORDER BY m.fecha DESC
            LIMIT :limite
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($movimientos as &$mov) {
            if ($mov['tipo'] === 'salida' && $mov['cantidad'] > 0) {
                $mov['cantidad'] = -$mov['cantidad'];
            }
        }

        return $movimientos;
    }

    public function obtenerConFiltros($fechaInicio = "", $fechaFin = "", $producto = "", $usuario = "", $tipo = "", $cantidad = "") {

        $sql = "
            SELECT 
                m.fecha,
                m.cantidad,
                m.tipo,
                p.nombre AS nombre_producto,
                u.nombre AS nombre_usuario,
                u.apellido AS apellido_usuario
            FROM inventario.movimientos m
            JOIN inventario.producto p ON p.id_producto = m.id_producto
            JOIN inventario.usuario u ON u.id_usuario = m.id_usuario
            WHERE 1 = 1
        ";

        $params = [];

        if ($fechaInicio !== "") {
            $sql .= " AND m.fecha >= :fechaInicio";
            $params[':fechaInicio'] = $fechaInicio . " 00:00:00";
        }

        if ($fechaFin !== "") {
            $sql .= " AND m.fecha <= :fechaFin";
            $params[':fechaFin'] = $fechaFin . " 23:59:59";
        }

        if ($producto !== "") {
            $sql .= " AND p.nombre ILIKE :producto";
            $params[':producto'] = "%$producto%";
        }

        if ($usuario !== "") {
            $sql .= " AND (u.nombre ILIKE :usuario OR u.apellido ILIKE :usuario)";
            $params[':usuario'] = "%$usuario%";
        }

        if ($tipo !== "") {
            $sql .= " AND m.tipo = :tipo";
            $params[':tipo'] = $tipo;
        }

        if ($cantidad !== "") {
            $sql .= " AND m.cantidad = :cantidad";
            $params[':cantidad'] = (int)$cantidad;
        }

        $sql .= " ORDER BY m.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($movimientos as &$mov) {
            if ($mov['tipo'] === 'salida' && $mov['cantidad'] > 0) {
                $mov['cantidad'] = -$mov['cantidad'];
            }
        }

        return $movimientos;
    }
}
?>
