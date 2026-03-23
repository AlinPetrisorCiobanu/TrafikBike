<?php


class control_panelcontroller {

    public function index()
    {
        $rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR", "MECANICO"];

        if (!isset($_SESSION['token']) || !isset($_SESSION['rol'])) {
            header("Location: http://localhost/practicas/trafikbike/public/login");
            exit;
        }

        if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
            header("Location: http://localhost/practicas/trafikbike/public");
            exit;
        }

        require "../views/control_panel/index.php";

    }

}