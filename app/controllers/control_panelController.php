<?php

require_once __DIR__ . '/../core/Controller.php';

class ControlPanelController extends Controller {

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

        // ✅ Render vista con layout
        return $this->view("control_panel/index", [
            "styles" => ["panel.css"]
        ]);
    }
}