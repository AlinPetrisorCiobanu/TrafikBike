<?php
require_once __DIR__ . '/../models/motos.php';
class motosController {

    public function index()
    {
        $motoModel = new Moto();
        $motos = $motoModel->getAllMotos();

        if($motos){
            $resultado = [
                "success" => true,
                "data" => $motos
            ];
        } else {
            $resultado = [
                "success" => false,
                "message" => "No se pudieron cargar las motos"
            ];
        }

        require "../views/motos/index.php";
    }

}