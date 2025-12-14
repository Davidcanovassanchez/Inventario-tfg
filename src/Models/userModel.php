<?php
require_once __DIR__ . '/../config/db.php';

class UserModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function obtenerUsuarios() {
        $query = "SELECT id_usuario, nombre, email, rol_id FROM inventario.usuario ORDER BY id_usuario DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorId($id) {
        $query = "SELECT * FROM inventario.usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearUsuario($nombre, $email, $password, $rol_id) {
        $query = "INSERT INTO inventario.usuario (nombre, email, password, rol_id) 
                  VALUES (:nombre, :email, :password, :rol_id)";
        $stmt = $this->conn->prepare($query);
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizarUsuario($id, $nombre, $email, $rol_id, $password = null) {
        if ($password) {
            $query = "UPDATE inventario.usuario 
                      SET nombre = :nombre, email = :email, rol_id = :rol_id, password = :password 
                      WHERE id_usuario = :id";
            $hashed = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $query = "UPDATE inventario.usuario 
                      SET nombre = :nombre, email = :email, rol_id = :rol_id 
                      WHERE id_usuario = :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
        if ($password) {
            $stmt->bindParam(':password', $hashed);
        }

        return $stmt->execute();
    }

    public function eliminarUsuario($id) {
        $query = "DELETE FROM inventario.usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
