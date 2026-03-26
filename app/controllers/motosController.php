<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/motos.php';

class motosController extends Controller {

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

    public function ver_moto()
    {
        $id_moto = $_GET['id'] ?? null;
        $moto = $id_moto ? $this->motoModel->getMotoById($id_moto) : null;
        return $this->view("motos/moto/index", [
            "styles" => ["moto.css"],
            "motos" => $moto ?? [],
            "error" => $moto ? null : "No se pudo cargar la moto"
        ]);
    }
}