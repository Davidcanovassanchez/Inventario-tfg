<?php
require_once __DIR__ . '/../config/db.php';

class Producto {
    private $conn;
    private $table = "inventario.producto";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conectar();
    }

    public function leerConFiltros($search = "", $orderBy = "fecha_creacion", $orderDir = "DESC", $fechaInicio = "", $fechaFin = "") {
        $orderByAllowed = ["precio_unitario", "stock", "fecha_creacion"];
        $orderDirAllowed = ["ASC", "DESC"];

        if (!in_array($orderBy, $orderByAllowed)) $orderBy = "fecha_creacion";
        if (!in_array($orderDir, $orderDirAllowed)) $orderDir = "DESC";

        $sql = "SELECT id_producto, nombre, descripcion, stock, precio_unitario, fecha_creacion
                FROM {$this->table}
                WHERE nombre ILIKE :search";

        if ($fechaInicio !== "") {
            $sql .= " AND fecha_creacion >= :fechaInicio";
        }
        if ($fechaFin !== "") {
            $sql .= " AND fecha_creacion <= :fechaFin";
        }

        $sql .= " ORDER BY $orderBy $orderDir";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":search", "%$search%");
        if ($fechaInicio !== "") {
            $stmt->bindValue(":fechaInicio", $fechaInicio . " 00:00:00");
        }
        if ($fechaFin !== "") {
            $stmt->bindValue(":fechaFin", $fechaFin . " 23:59:59");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerTodos() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id_producto ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTodos() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->conn->query($sql);
        return (int)$stmt->fetchColumn();
    }

    public function existeNombre($nombre) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE nombre = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre]);
        return $stmt->fetchColumn() > 0;
    }

    public function crear($nombre, $descripcion, $precio, $stock) {
        $sql = "INSERT INTO {$this->table} (nombre, descripcion, precio_unitario, stock)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $precio, $stock]);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $stock) {
        $sql = "UPDATE {$this->table}
                SET nombre=?, descripcion=?, precio_unitario=?, stock=?
                WHERE id_producto=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $precio, $stock, $id]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM {$this->table} WHERE id_producto=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function actualizarStock($id, $nuevoStock) {
        $sql = "UPDATE {$this->table} SET stock=? WHERE id_producto=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nuevoStock, $id]);
    }
}
?>
