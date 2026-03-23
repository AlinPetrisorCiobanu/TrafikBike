<?php

require_once __DIR__ . '/../core/controller.php';

class ContactoController extends Controller {

    public function index()
    {
        return $this->view("contacto/index", [
            "styles" => ["contacto.css"]
        ]);
    }

}