<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/Motos.php';

class MotosController extends Controller {

    private $motoModel;

    public function __construct(){
        $this->motoModel = new Moto();
    }

    public function index()
    {
        $motos = $this->motoModel->getAllMotos();

        return $this->view("motos/index", [
            "styles" => ["motos.css"],
            "motos" => $motos ?? [],
            "error" => $motos ? null : "No se pudieron cargar las motos"
        ]);
    }
}