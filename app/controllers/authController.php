<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/user.php';

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Mostrar formulario de login
    public function index() {
        return $this->view("auth/login/index", [
            "styles" => ["login.css"]
        ]);
    }

    // Procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect( BASE_URL . '/login');
        }

        $user_login = $_POST['user_login'] ?? '';
        $pass       = $_POST['pass'] ?? '';

        if (empty($user_login) || empty($pass)) {
            $_SESSION['login_error'] = "Todos los campos son obligatorios";
            return $this->redirect( BASE_URL . '/login');
        }

        // Buscar usuario en DB
        $user = $this->userModel->login($user_login, $pass);

        if (!$user) {
            $_SESSION['login_error'] = "Usuario o contraseña incorrectos";
            return $this->redirect( BASE_URL . '/login');
        }

        // Generar token para la sesión
        $token = bin2hex(random_bytes(32));

        // Guardar datos en sesión
        $_SESSION['id']        = $user["id_user"];
        $_SESSION['id_role']   = $user["id_role"];
        $_SESSION['rol']       = $user["rol"];
        $_SESSION['nombre']    = $user["nombre"];
        $_SESSION['email']     = $user["email"];
        $_SESSION['usuario']   = $user["usuario"];
        $_SESSION['is_active'] = $user["is_active"];
        $_SESSION['is_biker'] = $user["is_biker"];
        $_SESSION['permiso'] = $user["permiso"];
        $_SESSION['confirmed'] = $user["confirmed"];
        $_SESSION['token']     = $token;

        // Redirigir al home
        return $this->redirect(BASE_URL . '/');
    }

    // Cerrar sesión
    public function logout() {
        session_unset();
        session_destroy();
        return $this->redirect(BASE_URL . '/');
    }
}