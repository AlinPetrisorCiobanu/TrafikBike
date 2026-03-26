<?php

require_once __DIR__ . '/../core/database.php';

class Moto {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo;
    }

    public function getAllMotos() {

        try {

            $stmt = $this->pdo->prepare("SELECT 
            motos.id_moto, motos.matricula, motos.color,
            motos.anio, motos.km, motos.garantia_meses,
            motos.precio, motos.tipo, motos.permiso,
            motos.cilindrada,
            estado_motos.nombre AS estado,
            modelo.nombre AS modelo,
            marcas.nombre AS marca
            FROM motos 
            INNER JOIN modelo USING(id_modelo)
            INNER JOIN marcas USING(id_marca)
            INNER JOIN estado_motos USING(id_estado)
            WHERE motos.id_estado != 3
            ORDER BY id_moto DESC;");

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {

            return [];
        }
    }

    public function getMotoById($id) {

    try {

        $stmt = $this->pdo->prepare("
            SELECT 
                motos.id_moto, motos.matricula, motos.color,
                motos.anio, motos.km, motos.garantia_meses,
                motos.precio, motos.tipo, motos.permiso,
                motos.cilindrada,
                estado_motos.nombre AS estado,
                modelo.nombre AS modelo,
                marcas.nombre AS marca
            FROM motos 
            INNER JOIN modelo USING(id_modelo)
            INNER JOIN marcas USING(id_marca)
            INNER JOIN estado_motos USING(id_estado)
            WHERE motos.id_moto = :id
            AND motos.id_estado != 3
            LIMIT 1
        ");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {

        return null;
    }
}
}