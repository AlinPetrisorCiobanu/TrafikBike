<?php

require_once __DIR__ . '/../../core/controller.php';
require_once __DIR__ . '/../../models/taller.php';

class TallerController extends Controller
{
    private $rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR", "MECANICO"];
    private $taller;
    private $limit = 10;

    public function __construct()
    {
        $this->taller = new Taller();
        $this->checkAuth();
    }

    private function checkAuth(): void
    {
        if (!isset($_SESSION['token']) || !isset($_SESSION['rol'])) {
            $this->redirect('/login');
        }

        if (!in_array($_SESSION['rol'], $this->rolesPermitidos)) {
            $this->redirect('/');
        }
    }

    // Vista principal con filtros y paginación
    public function index(): void
    {
        $citas = $this->taller->getCitasForControlPanel();

        $filters = [];
        if (isset($_GET['estado']) && $_GET['estado'] !== '') {
            $filters['estado'] = $_GET['estado'];
            $citas = array_filter($citas, function ($cita) use ($filters) {
                return strtolower($cita['estado']) === strtolower($filters['estado']);
            });
        }

        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = $this->limit;
        $total_citas = count($citas);
        $citas = array_slice($citas, ($page - 1) * $perPage, $perPage);

        $this->view(
            "control_panel/taller/index",
            [
                "styles" => [
                    "control_panel/control_panel.css",
                    "control_panel/taller.css"
                ],
                "active" => "taller",
                "citas" => $citas,
                "current_page" => $page,
                "filters" => $filters,
                "total_citas" => $total_citas,
                "perPage" => $perPage
            ],
            ["layout" => "control_panel"]
        );
    }

    // Cambiar el estado de la cita vía AJAX
    public function modificar(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $id = $_POST['id'] ?? null;
        $estado = $_POST['estado'] ?? null;

        if (!$id || !$estado) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            exit;
        }

        $result = $this->taller->updateEstadoCita($id, $estado); // nueva función en el modelo

        if ($result['success']) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $result['error']]);
        }
        exit;
    }
}