<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/tienda.php';

class control_panel_tiendaController extends Controller {

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

        $productoModel = new Tienda();
        $productostos = $productoModel->getAllproductos() ;

        // ✅ Render vista con layout
        return $this->view("control_panel/tienda/index", [
            "styles" => ["control_panel/control_panel.css",
                        "control_panel/tienda.css"],
            "active" => "tienda",
            "productos" => $productostos
        ],[
            "layout" => "control_panel"
        ]);
    }
}