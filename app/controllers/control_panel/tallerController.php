<?php

require_once __DIR__ . '/../../core/controller.php';
require_once __DIR__ . '/../../models/taller.php';

class tallerController extends Controller {

    private $rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR", "MECANICO"];
    private $taller;

    public function __construct() {
        $this->taller = new Taller;
    }

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


        // 🔹 Obtener citas
        $citas = $this->taller->getAllCitas();

        // ✅ Render vista con layout
        return $this->view("control_panel/taller/index", [
            "styles" => [
                "control_panel/control_panel.css",
                "control_panel/taller.css"
            ],
            "active" => "taller",
            "citas" => $citas
        ],[
            "layout" => "control_panel"
        ]);
    }
}