<?php
require_once __DIR__ . "/../core/controller.php";

class homecontroller extends Controller
{
    public function index()
    {
        $this->view("home/index", [
            "styles" => ["index.css"]
        ]);
    }
}