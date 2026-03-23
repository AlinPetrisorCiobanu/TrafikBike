<?php

require_once __DIR__ . '/../core/controller.php';

class TallerController extends Controller {

    public function index()
    {
        return $this->view("taller/index", [
            "styles" => ["taller.css"]
        ]);
    }

}