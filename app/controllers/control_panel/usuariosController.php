<?php

require_once __DIR__ . '/../../core/controller.php';
require_once __DIR__ . '/../../models/user.php';

class usuariosController extends Controller {

    private $rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR", "MECANICO"];

    public function index()
    {
        // 🔐 Verificar sesión
        if (!isset($_SESSION['token']) || !isset($_SESSION['rol'])) {
            return $this->redirect('/login');
        }

        // 🔐 Verificar rol
        if (!in_array($_SESSION['rol'], $this->rolesPermitidos)) {
            return $this->redirect('/');
        }

        $userModel = new User();
        $users = $userModel->getAllUsers() ;

        // ✅ Render vista con layout
        return $this->view("control_panel/usuarios/index", [
            "styles" => [
                            "control_panel/control_panel.css",
                            "control_panel/usuarios.css"
                        ],
            "active" => "usuarios",
            "users" => $users
        ],[
            "layout" => "control_panel"
        ]);
    }
}