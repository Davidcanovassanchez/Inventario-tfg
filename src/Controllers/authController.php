<?php
require_once __DIR__ . '/../Config/db.php';

class AuthController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM inventario.usuario WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id_usuario'],
                    'rol' => $usuario['rol_id'] == 1 ? 'admin' : 'trabajador',
                    'nombre' => $usuario['nombre'],
                    'email' => $usuario['email']
                ];

                if ($_SESSION['usuario']['rol'] === 'admin') {
                    header("Location: ../Views/dashboard/admin.php");
                    exit;
                } else {
                    header("Location: ../Views/dashboard/trabajador.php");
                    exit;
                }

            } else {
                $_SESSION['mensaje'] = "Credenciales incorrectas";
                header("Location: ../Views/auth/login.php");
                exit;
            }

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
            header("Location: ../Views/auth/login.php");
            exit;
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: ../Views/auth/login.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $auth = new AuthController();
    $auth->login($_POST['email'], $_POST['password']);
}

if (isset($_GET['accion']) && $_GET['accion'] === 'cerrar_sesion') {
    $auth = new AuthController();
    $auth->logout();
}
?>
