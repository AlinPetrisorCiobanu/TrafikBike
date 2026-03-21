<?php
session_start();

class control_panelcontroller {

    public function index()
    {
        $rol = "";
        if(!isset($_SESSION['token'])){
            echo json_encode(['success'=>false,'message'=>'No hay usuario logueado']);
            exit;
        }
        if(!isset($_SESSION['rol'])){
            echo json_encode(['success'=>false,'message'=>'No hay usuario logueado']);
            exit;
        }else{
            $rol = $_SESSION['rol'];
        }
        if($rol === "SUPER_ADMIN" || $rol === "ADMIN" || $rol === "VENDEDOR" || $rol === "MECANICO"){
            require "../views/control_panel/index.php";
        }

    }

}