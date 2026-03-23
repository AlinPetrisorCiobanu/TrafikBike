<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/tienda.php';

class TiendaController extends Controller {

    private $productoModel;

    public function __construct() {
        $this->productoModel = new Tienda();
    }

    public function index() {
        $productos = $this->productoModel->getAllProductos();

        return $this->view("tienda/index", [
            "styles"   => ["tienda.css"],
            "productos"=> $productos ?? [],
            "error"    => $productos ? null : "No se pudieron cargar los productos"
        ]);
    }
}