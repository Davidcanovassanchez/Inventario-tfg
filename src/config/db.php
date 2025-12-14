<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "inventario_tfg";
    private $user = "postgres";
    private $password = "dbpass";
    public $conn;

    public function conectar() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}",
                $this->user,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
