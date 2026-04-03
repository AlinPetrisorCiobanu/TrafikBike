<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/taller.php';

class TallerController extends Controller {

    private $taller;

    public function __construct() {
        $this->taller = new Taller;
    }

    public function index()
    {
        // 🔒 Ideal: usar sesión
        $userId = $_SESSION['id'] ?? null;

        // 🔹 Obtener citas
        $citas = $this->taller->getCitasByUserId($userId);

        return $this->view("taller/index", [
            "styles" => ["taller.css"],
            "citas" => $citas
        ]);
    }

    public function index_cita()
    {

        return $this->view("taller/citas/index", [
            "styles" => ["citas.css"],
        ]);
    } 
}