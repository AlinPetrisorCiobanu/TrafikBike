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

    /**
 * Obtener citas filtradas y/o paginadas
 * @param array $filters ['estado' => 'Confirmada', 'mecanico_id' => 2]
 * @param int $limit Número máximo de resultados
 * @param int $offset Desplazamiento para paginación
 */
public function getCitasFiltradas(array $filters = [], int $limit = 50, int $offset = 0): array {
    $sql = "
        SELECT 
            c.id_cita,
            c.fecha_cita,
            c.fecha_entrada,
            c.fecha_solicitud,
            c.observaciones,
            u_mec.id_user AS mecanico_id,
            u_mec.nombre AS mecanico_nombre,
            u_mec.apellidos AS mecanico_apellidos,
            es.nombre AS estado,
            m.id_moto,
            m.matricula,
            mo.nombre AS modelo,
            ma.nombre AS marca,
            s.id_servicio,
            s.nombre AS servicio_nombre,
            cs.precio_base,
            cs.descuento_aplicado AS descuento,
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
        WHERE 1=1
    ";

    $params = [];

    // Aplicar filtros dinámicos
    if (!empty($filters['estado'])) {
        $sql .= " AND es.nombre = :estado";
        $params[':estado'] = $filters['estado'];
    }
    if (!empty($filters['mecanico_id'])) {
        $sql .= " AND mec.id_mecanico = :mecanico_id";
        $params[':mecanico_id'] = $filters['mecanico_id'];
    }
    if (!empty($filters['fecha_cita'])) {
        $sql .= " AND c.fecha_cita = :fecha_cita";
        $params[':fecha_cita'] = $filters['fecha_cita'];
    }

    $sql .= " ORDER BY c.id_cita DESC LIMIT :limit OFFSET :offset";

    $stmt = $this->pdo->prepare($sql);

    // PDO no permite bind directo de integers con execute + array, bindValue funciona mejor
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Reorganizar como en getAllCitas()
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
                    'id_mecanico' => $row['mecanico_id'],
                    'nombre' => $row['mecanico_nombre'],
                    'apellidos' => $row['mecanico_apellidos']
                ],
                'estado' => $row['estado'],
                'moto' => [
                    'id_moto' => $row['id_moto'],
                    'matricula' => $row['matricula'],
                    'modelo' => $row['modelo'],
                    'marca' => $row['marca']
                ],
                'servicios' => []
            ];
        }
        if ($row['id_servicio']) {
            $citas[$id]['servicios'][] = [
                'id_servicio' => $row['id_servicio'],
                'nombre' => $row['servicio_nombre'],
                'precio_base' => $row['precio_base'],
                'descuento' => $row['descuento'],
                'precio_final' => $row['precio_final']
            ];
        }
    }

    return array_values($citas);
}
public function getCitasForControlPanel() {
    $sql = "SELECT 
                c.id_cita,
                c.fecha_cita,
                c.fecha_entrada,
                c.fecha_solicitud,
                c.observaciones,
                u_mec.id_user AS id_mecanico,
                u_mec.nombre AS mecanico_nombre,
                u_mec.apellidos AS mecanico_apellidos,
                es.nombre AS estado,
                m.id_moto,
                m.matricula,
                mo.nombre AS modelo_nombre,
                ma.nombre AS marca_nombre
            FROM cita_taller c
            INNER JOIN mecanico mec ON c.id_mecanico = mec.id_mecanico
            INNER JOIN users u_mec ON mec.id_user = u_mec.id_user
            INNER JOIN estado_servicio es ON c.id_estado = es.id_estado
            LEFT JOIN motos m ON c.id_moto = m.id_moto
            LEFT JOIN modelo mo ON m.id_modelo = mo.id_modelo
            LEFT JOIN marcas ma ON mo.id_marca = ma.id_marca
            ORDER BY c.id_cita DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar array final
    $citas = [];
    foreach ($rows as $row) {
        $id = $row['id_cita'];
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
            'servicios' => [] // los agregaremos después
        ];
    }

    // Traer servicios por cita
    $sqlServicios = "SELECT cs.id_cita, s.nombre, cs.precio_base, cs.descuento_aplicado, cs.precio_final
                     FROM cita_servicios cs
                     INNER JOIN servicios s ON cs.id_servicio = s.id_servicio";
    $stmtServicios = $this->pdo->prepare($sqlServicios);
    $stmtServicios->execute();
    $servRows = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);

    foreach ($servRows as $serv) {
        $id = $serv['id_cita'];
        if (isset($citas[$id])) {
            $citas[$id]['servicios'][] = [
                'nombre' => $serv['nombre'],
                'precio_base' => $serv['precio_base'],
                'descuento' => $serv['descuento_aplicado'],
                'precio_final' => $serv['precio_final']
            ];
        }
    }

    return array_values($citas);
}
public function getCitaById($id)
{
    $sql = "SELECT 
                c.id_cita, c.fecha_cita, c.fecha_entrada, c.fecha_solicitud, c.observaciones,
                u_mec.id_user AS id_mecanico, u_mec.nombre AS mecanico_nombre, u_mec.apellidos AS mecanico_apellidos,
                es.id_estado, es.nombre AS estado,
                m.id_moto, m.matricula, mo.nombre AS modelo_nombre, ma.nombre AS marca_nombre
            FROM cita_taller c
            INNER JOIN mecanico mec ON c.id_mecanico = mec.id_mecanico
            INNER JOIN users u_mec ON mec.id_user = u_mec.id_user
            INNER JOIN estado_servicio es ON c.id_estado = es.id_estado
            LEFT JOIN motos m ON c.id_moto = m.id_moto
            LEFT JOIN modelo mo ON m.id_modelo = mo.id_modelo
            LEFT JOIN marcas ma ON mo.id_marca = ma.id_marca
            WHERE c.id_cita = :id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cita) return null;

    // Traer servicios
    $sqlServicios = "SELECT cs.id_cita_servicio, s.id_servicio, s.nombre, cs.precio_base, cs.descuento_aplicado, cs.precio_final
                     FROM cita_servicios cs
                     INNER JOIN servicios s ON cs.id_servicio = s.id_servicio
                     WHERE cs.id_cita = :id";
    $stmtServicios = $this->pdo->prepare($sqlServicios);
    $stmtServicios->execute([':id' => $id]);
    $servicios = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);

    $cita['servicios'] = $servicios;

    return $cita;
}

public function updateCita($id, $data)
{
    try {
        $this->pdo->beginTransaction();

        // Actualizar datos básicos
        $sql = "UPDATE cita_taller SET 
                    id_mecanico = :id_mecanico,
                    id_estado = :id_estado,
                    id_moto = :id_moto,
                    fecha_cita = :fecha_cita,
                    fecha_entrada = :fecha_entrada,
                    observaciones = :observaciones
                WHERE id_cita = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_mecanico' => $data['id_mecanico'],
            ':id_estado' => $data['id_estado'],
            ':id_moto' => $data['id_moto'] ?? null,
            ':fecha_cita' => $data['fecha_cita'],
            ':fecha_entrada' => $data['fecha_entrada'] ?? null,
            ':observaciones' => $data['observaciones'] ?? null,
            ':id' => $id
        ]);

        // Eliminar servicios antiguos
        $sqlDel = "DELETE FROM cita_servicios WHERE id_cita = :id";
        $stmtDel = $this->pdo->prepare($sqlDel);
        $stmtDel->execute([':id' => $id]);

        // Insertar servicios nuevos
        if (!empty($data['servicios'])) {
            $sqlServicio = "INSERT INTO cita_servicios (id_cita, id_servicio, precio_base, descuento_aplicado, precio_final)
                            VALUES (:id_cita, :id_servicio, :precio_base, :descuento, :precio_final)";
            $stmtServicio = $this->pdo->prepare($sqlServicio);

            foreach ($data['servicios'] as $servicio) {
                $precio_base = $servicio['precio_base'];
                $descuento = $servicio['descuento'] ?? 0;
                $precio_final = $precio_base - $descuento;

                $stmtServicio->execute([
                    ':id_cita' => $id,
                    ':id_servicio' => $servicio['id_servicio'],
                    ':precio_base' => $precio_base,
                    ':descuento' => $descuento,
                    ':precio_final' => $precio_final
                ]);
            }
        }

        $this->pdo->commit();
        return ['success' => true];

    } catch (Exception $e) {
        $this->pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
// Cambiar solo el estado de una cita
public function updateCitaEstado($id_cita, $nuevoEstado) {
    try {
        $sql = "UPDATE cita_taller c
                INNER JOIN estado_servicio es ON es.nombre = :estado
                SET c.id_estado = es.id_estado
                WHERE c.id_cita = :id_cita";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':estado' => $nuevoEstado,
            ':id_cita' => $id_cita
        ]);

        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
 public function updateEstadoCita($id_cita, $nuevo_estado) {
        try {
            $sql = "UPDATE cita_taller c
                    INNER JOIN estado_servicio es ON es.nombre = :estado
                    SET c.id_estado = es.id_estado
                    WHERE c.id_cita = :id_cita";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':estado' => $nuevo_estado,
                ':id_cita' => $id_cita
            ]);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

}