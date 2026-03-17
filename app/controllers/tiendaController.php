<?php
require_once __DIR__ . '/../models/tienda.php';
class tiendaController {

    public function index()
    {
        $productoModel = new Tienda();
        $productos = $productoModel->getAllProductos();

        if($productos){
            $resultado = [
                "success" => true,
                "data" => $productos
            ];
        } else {
            $resultado = [
                "success" => false,
                "message" => "No se pudieron cargar los Productos"
            ];
        }

        require "../views/tienda/index.php";

    }

}