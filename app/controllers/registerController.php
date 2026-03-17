<?php
session_start();
require_once __DIR__ . '/../models/User.php';

class registerController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        require "../views/auth/register/index.php";
    }

    public function register() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nombre'    => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'dni'       => $_POST['dni'] ?? '',
            'telefono'  => $_POST['telefono'] ?? '',
            'email'     => $_POST['email'] ?? '',
            'usuario'   => $_POST['usuario'] ?? '',
            'password'  => $_POST['password'] ?? '',
            'id_role'   => 7
        ];

        // Registro + autologin
        $user = $this->userModel->register($data);

        if($user) {
            $_SESSION['user'] = $user;
            
            $token = bin2hex(random_bytes(32));

            $_SESSION['user']      = $user;
            $_SESSION['id']        = $user["id_user"];
            $_SESSION['id_role']   = $user["id_role"];
            $_SESSION['nombre']    = $user["nombre"];
            $_SESSION['email']     = $user["email"];
            $_SESSION['usuario']   = $user["usuario"];
            $_SESSION['is_active'] = $user["is_active"];
            $_SESSION['token']     = $token;
            header('Location: ' . BASE_URL . '/');
            exit;
        } else {
            $_SESSION['login_error'] = "Error al registrar el usuario";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }
    }
}
}