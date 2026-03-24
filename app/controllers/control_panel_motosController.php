<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/motos.php';

class control_panel_motosController extends Controller {

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

        $motoModel = new Moto();
        $motos = $motoModel->getAllMotos() ;

        // ✅ Render vista con layout
        return $this->view("control_panel/motos/index", [
            "styles" => ["control_panel/control_panel.css",
                        "control_panel/motos.css"],
            "active" => "motos",
            "motos" => $motos
        ],[
            "layout" => "control_panel"
        ]);
    }
}