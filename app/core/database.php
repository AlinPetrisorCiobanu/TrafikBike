<?php

class database {

    private static $instance = null;
    public $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            // mysql://root:SobjnesZZgUMahpEgizcEVXgwaGaLApe@turntable.proxy.rlwy.net:15179/railway
        } catch (PDOException $e) {
            die("Error de conexión: ".$e->getMessage());
        }
    }

    public static function getInstance() {
        if(self::$instance === null){
            self::$instance = new database();
        }
        return self::$instance;
    }
}