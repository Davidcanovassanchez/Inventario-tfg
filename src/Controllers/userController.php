<?php
require_once __DIR__ . '/../Models/userModel.php';
require_once __DIR__ . '/../Config/sesion.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function listarUsuarios() {
        return $this->userModel->obtenerUsuarios();
    }

    public function index() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: /tfg/Inventory-tfg/src/Views/auth/login.php");
            exit;
        }

        $usuarios = $this->listarUsuarios();
        include __DIR__ . '/../Views/usuarios/listar_usuarios.php';
    }

    public function crear() {
        $this->verificarAcceso();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $rol_id = (int)$_POST['rol_id'];

            $ok = $this->userModel->crearUsuario($nombre, $email, $password, $rol_id);
            $_SESSION['mensaje'] = $ok ? "Usuario creado correctamente." : "Error al crear el usuario.";

            header("Location: /tfg/Inventory-tfg/src/Controllers/userController.php?accion=index");
            exit;
        }

        include __DIR__ . '/../Views/usuarios/crear_usuario.php';
    }

    public function editar() {
        $this->verificarAcceso();

        if (!isset($_GET['id'])) {
            header("Location: /tfg/Inventory-tfg/src/Controllers/userController.php?accion=index");
            exit;
        }

        $id = (int)$_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $rol_id = (int)$_POST['rol_id'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            $ok = $this->userModel->actualizarUsuario($id, $nombre, $email, $rol_id, $password);
            $_SESSION['mensaje'] = $ok ? "Usuario actualizado correctamente." : "Error al actualizar el usuario.";

            header("Location: /tfg/Inventory-tfg/src/Controllers/userController.php?accion=index");
            exit;
        }

        $usuario = $this->userModel->obtenerUsuarioPorId($id);
        include __DIR__ . '/../Views/usuarios/editar_usuario.php';
    }

    public function eliminar() {
        $this->verificarAcceso();

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $ok = $this->userModel->eliminarUsuario($id);
            $_SESSION['mensaje'] = $ok ? "Usuario eliminado correctamente." : "Error al eliminar el usuario.";
        }

        header("Location: /tfg/Inventory-tfg/src/Controllers/userController.php?accion=index");
        exit;
    }

    private function verificarAcceso() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: /tfg/Inventory-tfg/src/Views/auth/login.php");
            exit;
        }
    }
}

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    $controller = new UserController();
    $accion = $_GET['accion'] ?? 'index';

    switch ($accion) {
        case 'index': $controller->index(); break;
        case 'crear': $controller->crear(); break;
        case 'editar': $controller->editar(); break;
        case 'eliminar': $controller->eliminar(); break;
        default: $controller->index(); break;
    }
}
?>
