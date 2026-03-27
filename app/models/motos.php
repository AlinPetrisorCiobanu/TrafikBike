<?php
require_once __DIR__ . '/../core/database.php';

class Moto {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo;
    }

    //-- Traer marcas y modelos
    public function get_marcas_modelos()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    m.id_marca,
                    m.nombre AS nombre_marca,
                    mo.id_modelo,
                    mo.nombre AS nombre_modelo
                FROM marcas m
                LEFT JOIN modelo mo ON mo.id_marca = m.id_marca
                ORDER BY m.nombre, mo.nombre
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }

    //-- Construir filtros dinámicos
    private function buildFilters(&$params, $filters)
    {
        $where = " WHERE motos.id_estado != 3"; // por defecto excluir estado 3

        // Marca
        if(!empty($filters['id_marca'])){
            $where .= " AND marcas.id_marca = :id_marca";
            $params[':id_marca'] = $filters['id_marca'];
        }

        // Modelo
        if(!empty($filters['id_modelo'])){
            $where .= " AND modelo.id_modelo = :id_modelo";
            $params[':id_modelo'] = $filters['id_modelo'];
        }

        // Kilometraje máximo
        if(!empty($filters['km_range']) && is_numeric($filters['km_range'])){
            $where .= " AND motos.km <= :km_range";
            $params[':km_range'] = $filters['km_range'];
        }

        // Año mínimo
        if(!empty($filters['anio']) && is_numeric($filters['anio'])){
            $where .= " AND motos.anio >= :anio";
            $params[':anio'] = $filters['anio'];
        }

        // Precio máximo
        if(!empty($filters['precio_range']) && is_numeric($filters['precio_range'])){
            $where .= " AND motos.precio <= :precio_range";
            $params[':precio_range'] = $filters['precio_range'];
        }

        // Permiso
        if(!empty($filters['permiso'])){
            $where .= " AND motos.permiso = :permiso";
            $params[':permiso'] = $filters['permiso'];
        }

        // Cilindrada máxima
        if(!empty($filters['cilindrada_range']) && is_numeric($filters['cilindrada_range'])){
            $where .= " AND motos.cilindrada <= :cilindrada_range";
            $params[':cilindrada_range'] = $filters['cilindrada_range'];
        }

        // Tipo
        if(!empty($filters['tipo'])){
            $where .= " AND motos.tipo = :tipo";
            $params[':tipo'] = $filters['tipo'];
        }

        // Matrícula
        if(!empty($filters['matricula'])){
            $where .= " AND motos.matricula LIKE :matricula";
            $params[':matricula'] = '%' . $filters['matricula'] . '%';
        }

        // VIN
        if(!empty($filters['vin'])){
            $where .= " AND motos.vin LIKE :vin";
            $params[':vin'] = '%' . $filters['vin'] . '%';
        }

        return $where;
    }

    //-- Obtener motos con filtros y paginación
    public function getMotosPaginated($limit = 10, $offset = 0, array $filters = [])
    {
        try {
            $params = [];
            $sql = "SELECT 
                        motos.id_moto, motos.matricula, motos.vin, motos.color,
                        motos.anio, motos.km, motos.garantia_meses,
                        motos.precio, motos.tipo, motos.permiso,
                        motos.cilindrada,
                        estado_motos.nombre AS estado,
                        modelo.nombre AS modelo,
                        marcas.nombre AS marca
                    FROM motos
                    INNER JOIN modelo USING(id_modelo)
                    INNER JOIN marcas USING(id_marca)
                    INNER JOIN estado_motos USING(id_estado)";

            // Agregar filtros dinámicos
            $sql .= $this->buildFilters($params, $filters);

            // Orden y paginación
            $sql .= " ORDER BY motos.id_moto DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);

            // Bind de parámetros
            foreach($params as $key => $val){
                $stmt->bindValue($key, $val, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){
            return [];
        }
    }

    //-- Contar motos para paginación
    public function countMotos(array $filters = [])
    {
        try{
            $params = [];
            $sql = "SELECT COUNT(*) as total
                    FROM motos
                    INNER JOIN modelo USING(id_modelo)
                    INNER JOIN marcas USING(id_marca)
                    INNER JOIN estado_motos USING(id_estado)";

            $sql .= $this->buildFilters($params, $filters);

            $stmt = $this->pdo->prepare($sql);
            foreach($params as $key=>$val){
                $stmt->bindValue($key,$val,PDO::PARAM_STR);
            }
            $stmt->execute();

            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch(PDOException $e){
            return 0;
        }
    }

    //-- Obtener una moto por id
    public function getMotoById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    motos.id_moto, motos.matricula, motos.vin, motos.color,
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