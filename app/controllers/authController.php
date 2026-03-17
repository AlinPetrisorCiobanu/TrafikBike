<?php
require_once __DIR__ . '/../models/user.php';
session_start();

class AuthController {

    private $userModel;

    public function __construct(){
        $this->userModel = new User();
    }

    public function index(){
        {
            require "../views/auth/login/index.php";
        }
    }


    // Procesar login
   public function login(){
    $user_login = $_POST['user_login'] ?? '';
    $pass = $_POST['pass'] ?? '';

    $user = $this->userModel->login($user_login, $pass);

    if($user){
        // verificar contraseña usando la variable correcta
        if (!password_verify($pass, $user['password_hash'])) {
            $_SESSION['login_error'] = 'Usuario o contraseña incorrectos';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // generar token personalizado
        $token = bin2hex(random_bytes(32));

        $_SESSION['id'] = $user["id_user"];
        $_SESSION['id_role'] = $user["id_role"];
        $_SESSION['nombre'] = $user["nombre"];
        $_SESSION['email'] = $user["email"];
        $_SESSION['usuario'] = $user["usuario"];
        $_SESSION['is_active'] = $user["is_active"];
        $_SESSION['token'] = $token;

        header('Location: ' . BASE_URL . '/');
        exit;
    } else {
        $_SESSION['login_error'] = "Usuario o contraseña incorrectos";
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

    public function logout(){ 
        session_unset();     
        session_destroy();     
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}