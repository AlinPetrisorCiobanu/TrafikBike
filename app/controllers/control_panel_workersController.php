<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/workers.php';

class control_panel_workersController extends Controller {

    private $rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR", "MECANICO"];

    public function index()
    {
        if (!isset($_SESSION['token']) || !isset($_SESSION['rol'])) {
            return $this->redirect('/login');
        };

        if (!in_array($_SESSION['rol'], $this->rolesPermitidos)) {
            return $this->redirect('/');
        };

        $workersModel = new Workers();

        return $this->view("control_panel/workers/index", [
            "styles" => ["control_panel/control_panel.css", "control_panel/workers.css"]
        ],[
            "layout" => "control_panel"
        ]);
    }

    public function addWorker()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $tipo = strtolower($_POST['tipo'] ?? '');
        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $dni = $_POST['dni'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $email = $_POST['email'] ?? '';
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        $sueldo = $_POST['sueldo'] ?? 0;
        $extra = $_POST['extra'] ?? null;

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $workersModel = new Workers();

        try {
            $id_user = $workersModel->addUser($tipo, $nombre, $apellidos, $dni, $telefono, $email, $usuario, $password_hash, $sueldo, $extra);
            $_SESSION['message'] = ucfirst($tipo) . " creado correctamente. ID: $id_user";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        return $this->redirect('/control/panel/workers');
    }
}
}