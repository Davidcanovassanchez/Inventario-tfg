<?php
require_once __DIR__ . '/../config/db.php';

class Usuario {
    private $conn;
    private $table_name = "inventario.usuario";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function verificarLogin($email, $password) {
        $query = "SELECT u.*, r.nombre_rol 
                  FROM {$this->table_name} u
                  JOIN inventario.rol r ON u.rol_id = r.id_rol
                  WHERE u.email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        $queryCheck = "SELECT (u.password = crypt(:password, u.password)) AS valido 
                       FROM {$this->table_name} u WHERE u.email = :email";
        $check = $this->conn->prepare($queryCheck);
        $check->execute([':password' => $password, ':email' => $email]);
        $result = $check->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $result['valido'] === 't') {
            return $usuario;
        } else {
            return false;
        }
    }

    public function contarTodos() {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table_name}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function leerConFiltros($id = "", $nombre = "", $email = "", $rol = "", $orderBy = "id_usuario", $orderDir = "ASC") {
        $allowedOrder = ["id_usuario", "nombre", "email", "rol_id"];
        if (!in_array($orderBy, $allowedOrder)) $orderBy = "id_usuario";
        $orderDir = strtoupper($orderDir) === "DESC" ? "DESC" : "ASC";

        $sql = "SELECT u.*, r.nombre_rol FROM {$this->table_name} u 
                JOIN inventario.rol r ON u.rol_id = r.id_rol 
                WHERE 1=1";

        if ($id !== "") $sql .= " AND u.id_usuario = :id";
        if ($nombre !== "") $sql .= " AND u.nombre ILIKE :nombre";
        if ($email !== "") $sql .= " AND u.email ILIKE :email";
        if ($rol !== "") $sql .= " AND r.nombre_rol = :rol";

        $sql .= " ORDER BY $orderBy $orderDir";

        $stmt = $this->conn->prepare($sql);

        if ($id !== "") $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        if ($nombre !== "") $stmt->bindValue(":nombre", "%$nombre%");
        if ($email !== "") $stmt->bindValue(":email", "%$email%");
        if ($rol !== "") $stmt->bindValue(":rol", $rol);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
