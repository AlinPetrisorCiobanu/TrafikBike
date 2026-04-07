<?php
require_once __DIR__ . '/../core/database.php';

class Taller {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo;
    }

    // Obtener todas las citas
    public function getAllCitas() {
        $sql = "SELECT 
                    c.id_cita,
                    c.fecha_cita,
                    c.fecha_entrada,
                    c.fecha_solicitud,
                    c.observaciones,
                    mec.id_mecanico,
                    u_mec.nombre AS mecanico_nombre,
                    u_mec.apellidos AS mecanico_apellidos,
                    es.nombre AS estado,
                    m.id_moto,
                    m.matricula,
                    mo.nombre AS modelo_nombre,
                    ma.nombre AS marca_nombre,
                    cs.id_cita_servicio,
                    s.nombre AS servicio_nombre,
                    cs.precio_base,
                    cs.descuento_aplicado,
                    cs.precio_final
                FROM cita_taller c
                INNER JOIN mecanico mec ON c.id_mecanico = mec.id_mecanico
                INNER JOIN users u_mec ON mec.id_user = u_mec.id_user
                INNER JOIN estado_servicio es ON c.id_estado = es.id_estado
                LEFT JOIN motos m ON c.id_moto = m.id_moto
                LEFT JOIN modelo mo ON m.id_modelo = mo.id_modelo
                LEFT JOIN marcas ma ON mo.id_marca = ma.id_marca
                LEFT JOIN cita_servicios cs ON c.id_cita = cs.id_cita
                LEFT JOIN servicios s ON cs.id_servicio = s.id_servicio
                ORDER BY c.id_cita DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $citas = [];

        foreach ($rows as $row) {
            $id = $row['id_cita'];

            if (!isset($citas[$id])) {
                $citas[$id] = [
                    'id_cita' => $id,
                    'fecha_cita' => $row['fecha_cita'],
                    'fecha_entrada' => $row['fecha_entrada'],
                    'fecha_solicitud' => $row['fecha_solicitud'],
                    'observaciones' => $row['observaciones'],
                    'mecanico' => [
                        'id_mecanico' => $row['id_mecanico'],
                        'nombre' => $row['mecanico_nombre'],
                        'apellidos' => $row['mecanico_apellidos']
                    ],
                    'estado' => $row['estado'],
                    'moto' => [
                        'id_moto' => $row['id_moto'],
                        'matricula' => $row['matricula'],
                        'modelo' => $row['modelo_nombre'],
                        'marca' => $row['marca_nombre']
                    ],
                    'servicios' => []
                ];
            }

            if ($row['id_cita_servicio']) {
                $citas[$id]['servicios'][] = [
                    'id_cita_servicio' => $row['id_cita_servicio'],
                    'nombre' => $row['servicio_nombre'],
                    'precio_base' => $row['precio_base'],
                    'descuento' => $row['descuento_aplicado'],
                    'precio_final' => $row['precio_final']
                ];
            }
        }

        return array_values($citas);
    }

    // Obtener todas las motos según rol
    public function getMotosByRole($user_role) {
        $roles_disponibles = ['SUPER_ADMIN','ADMIN','VENDEDOR'];
        $estado = in_array($user_role, $roles_disponibles) ? 'Disponible' : 'Vendida';

        $sql = "SELECT 
                    m.id_moto,
                    m.matricula,
                    mo.nombre AS modelo,
                    ma.nombre AS marca,
                    es.nombre AS estado
                FROM motos m
                LEFT JOIN modelo mo ON m.id_modelo = mo.id_modelo
                LEFT JOIN marcas ma ON mo.id_marca = ma.id_marca
                LEFT JOIN estado_motos es ON m.id_estado = es.id_estado
                WHERE es.nombre = :estado
                ORDER BY ma.nombre, mo.nombre";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':estado' => $estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los servicios
    public function getServicios() {
        $sql = "SELECT * FROM servicios ORDER BY nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear nueva cita
    public function createCita($data) {
        try {
            $this->pdo->beginTransaction();

            $sqlCita = "INSERT INTO cita_taller (
                            id_mecanico, id_estado, id_moto, fecha_cita, fecha_entrada, observaciones
                        ) VALUES (
                            :id_mecanico, :id_estado, :id_moto, :fecha_cita, :fecha_entrada, :observaciones
                        )";

            $stmt = $this->pdo->prepare($sqlCita);
            $stmt->execute([
                ':id_mecanico' => $data['id_mecanico'],
                ':id_estado' => $data['id_estado'] ?? 1,
                ':id_moto' => $data['id_moto'] ?? null,
                ':fecha_cita' => $data['fecha_cita'],
                ':fecha_entrada' => $data['fecha_entrada'] ?? null,
                ':observaciones' => $data['observaciones'] ?? null
            ]);

            $id_cita = $this->pdo->lastInsertId();

            if (!empty($data['servicios'])) {
                $sqlServicio = "INSERT INTO cita_servicios (
                                    id_cita, id_servicio, precio_base, descuento_aplicado, precio_final
                                ) VALUES (
                                    :id_cita, :id_servicio, :precio_base, :descuento, :precio_final
                                )";

                $stmtServicio = $this->pdo->prepare($sqlServicio);

                foreach ($data['servicios'] as $servicio) {
                    $precio_base = $servicio['precio_base'];
                    $descuento = $servicio['descuento'] ?? 0;
                    $precio_final = $precio_base - $descuento;

                    $stmtServicio->execute([
                        ':id_cita' => $id_cita,
                        ':id_servicio' => $servicio['id_servicio'],
                        ':precio_base' => $precio_base,
                        ':descuento' => $descuento,
                        ':precio_final' => $precio_final
                    ]);
                }
            }

            $this->pdo->commit();
            return ['success' => true, 'id_cita' => $id_cita];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}