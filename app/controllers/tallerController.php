<?php
require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/taller.php';

class TallerController extends Controller {
    private $taller;

    public function __construct() {
        $this->taller = new Taller;
    }

    // Listado de citas
    public function index() {
        $citas = $this->taller->getAllCitas();
        return $this->view("taller/index", [
            "styles" => ["taller.css"],
            "citas" => $citas
        ]);
    }

    // Formulario de nueva cita
    public function nuevaCita() {
        $user_role = $_SESSION['user_role'] ?? 'USER';
        $motos = $this->taller->getMotosByRole($user_role);
        $servicios = $this->taller->getServicios();

        return $this->view("taller/citas/index", [
            "styles" => ["citas.css"],
            "motos" => $motos,
            "servicios" => $servicios,
            "user_role" => $user_role
        ]);
    }

    // Crear cita desde POST
    public function crearCita() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_mecanico' => $_POST['id_mecanico'] ?? 1,
                'id_moto' => $_POST['id_moto'] ?? null,
                'fecha_cita' => $_POST['fecha_cita'],
                'observaciones' => $_POST['observaciones'] ?? null,
                'servicios' => []
            ];

            if (!empty($_POST['servicios'])) {
                foreach ($_POST['servicios'] as $id_servicio) {
                    $data['servicios'][] = [
                        'id_servicio' => $id_servicio,
                        'precio_base' => 50,
                        'descuento' => 0
                    ];
                }
            }

            $result = $this->taller->createCita($data);

            if ($result['success']) {
                header("Location: " . BASE_URL . "/taller");
                exit;
            } else {
                echo "Error: " . $result['error'];
            }
        }
    }
}