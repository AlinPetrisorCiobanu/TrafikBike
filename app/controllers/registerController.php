<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/user.php';

class RegisterController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Mostrar formulario de registro
    public function index() {
        return $this->view("auth/register/index", [
            "styles" => ["register.css"]
        ]);
    }

    // Procesar registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/register');
        }

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

        // Registro del usuario
        $user = $this->userModel->register($data);

        if (!$user) {
            $_SESSION['login_error'] = "Error al registrar el usuario";
            return $this->redirect('/register');
        }

        // Autologin después del registro
        $token = bin2hex(random_bytes(32));

        $_SESSION['id']        = $user["id_user"];
        $_SESSION['id_role']   = $user["id_role"];
        $_SESSION['rol']       = $user["rol"];
        $_SESSION['nombre']    = $user["nombre"];
        $_SESSION['email']     = $user["email"];
        $_SESSION['usuario']   = $user["usuario"];
        $_SESSION['is_active'] = $user["is_active"];
        $_SESSION['token']     = $token;

        return $this->redirect('/');
    }
}