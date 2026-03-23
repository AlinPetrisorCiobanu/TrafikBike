<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/user.php';

class UsuarioController extends Controller {

    public function index()
    {
        // 🔐 Verificar sesión
        if (!isset($_SESSION['token'])) {
            return $this->redirect('/login');
        }

        $userModel = new User();
        $userId = $_SESSION['id'] ?? null;

        $user = $userId ? $userModel->getUserById($userId) : null;

        if (!$user) {
            return $this->redirect('/');
        }

        return $this->view("usuario/index", [
            "styles" => ["usuario.css"],
            "usuario"   => $user
        ]);
    }
}