<?php

require_once __DIR__ . '/../core/database.php';


class Taller {
     private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo;
    }

   public function getAllCitas() {
    $sql = "SELECT 
                c.id_cita,
                c.fecha_cita,
                c.fecha_entrada,
                c.fecha_solicitud,
                c.observaciones,

                -- Usuario cliente
                u.id_user,
                u.nombre AS usuario_nombre,
                u.apellidos AS usuario_apellidos,

                -- Moto
                m.id_moto,
                m.matricula,
                m.color,
                m.tipo,
                m.cilindrada,
                m.anio,
                mo.id_modelo,
                mo.nombre AS modelo_nombre,
                ma.id_marca,
                ma.nombre AS marca_nombre,

                -- Mecánico
                mec.id_mecanico,
                u_mec.id_user AS id_user_mec,
                u_mec.nombre AS mecanico_nombre,
                u_mec.apellidos AS mecanico_apellidos,

                -- Estado de cita
                es.nombre AS estado,

                -- Servicios
                cs.id_cita_servicio,
                s.nombre AS servicio_nombre,
                cs.precio_base,
                cs.descuento_aplicado,
                cs.precio_final

            FROM cita_taller c

            LEFT JOIN users u ON c.id_user = u.id_user

            LEFT JOIN motos m ON c.id_moto = m.id_moto
            LEFT JOIN modelo mo ON m.id_modelo = mo.id_modelo
            LEFT JOIN marcas ma ON mo.id_marca = ma.id_marca

            INNER JOIN mecanico mec ON c.id_mecanico = mec.id_mecanico
            INNER JOIN users u_mec ON mec.id_user = u_mec.id_user

            INNER JOIN estado_servicio es ON c.id_estado = es.id_estado

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

                'usuario' => [
                    'id_user' => $row['id_user'],
                    'nombre' => $row['usuario_nombre'],
                    'apellidos' => $row['usuario_apellidos']
                ],

                'moto' => [
                    'id_moto' => $row['id_moto'],
                    'matricula' => $row['matricula'],
                    'color' => $row['color'],
                    'tipo' => $row['tipo'],
                    'cilindrada' => $row['cilindrada'],
                    'anio' => $row['anio'],
                    'id_modelo' => $row['id_modelo'],
                    'modelo' => $row['modelo_nombre'],
                    'id_marca' => $row['id_marca'],
                    'marca' => $row['marca_nombre']
                ],

                'mecanico' => [
                    'id_mecanico' => $row['id_mecanico'],
                    'id_user' => $row['id_user_mec'],
                    'nombre' => $row['mecanico_nombre'],
                    'apellidos' => $row['mecanico_apellidos']
                ],

                'estado' => $row['estado'],

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

    public function getCitasByUserId($id_user) {
    $sql = "SELECT 
                c.id_cita,
                c.fecha_cita,
                c.fecha_entrada,
                c.fecha_solicitud,
                c.observaciones,

                -- Moto
                m.id_moto,
                m.matricula,
                m.color,
                m.tipo,
                m.cilindrada,
                m.anio,
                mo.id_modelo,
                mo.nombre AS modelo_nombre,
                ma.id_marca,
                ma.nombre AS marca_nombre,

                -- Mecánico
                mec.id_mecanico,
                u_mec.id_user AS id_user_mec,
                u_mec.nombre AS mecanico_nombre,
                u_mec.apellidos AS mecanico_apellidos,

                -- Estado de cita
                es.nombre AS estado,

                -- Servicios
                cs.id_cita_servicio,
                s.nombre AS servicio_nombre,
                cs.precio_base,
                cs.descuento_aplicado,
                cs.precio_final

            FROM cita_taller c

            LEFT JOIN motos m ON c.id_moto = m.id_moto
            LEFT JOIN modelo mo ON m.id_modelo = mo.id_modelo
            LEFT JOIN marcas ma ON mo.id_marca = ma.id_marca

            INNER JOIN mecanico mec ON c.id_mecanico = mec.id_mecanico
            INNER JOIN users u_mec ON mec.id_user = u_mec.id_user

            INNER JOIN estado_servicio es ON c.id_estado = es.id_estado

            LEFT JOIN cita_servicios cs ON c.id_cita = cs.id_cita
            LEFT JOIN servicios s ON cs.id_servicio = s.id_servicio

            WHERE c.id_user = :id_user
            ORDER BY c.fecha_cita DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_user' => $id_user]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🔹 Agrupar resultados
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

                'moto' => [
                    'id_moto' => $row['id_moto'],
                    'matricula' => $row['matricula'],
                    'color' => $row['color'],
                    'tipo' => $row['tipo'],
                    'cilindrada' => $row['cilindrada'],
                    'anio' => $row['anio'],
                    'id_modelo' => $row['id_modelo'],
                    'modelo' => $row['modelo_nombre'],
                    'id_marca' => $row['id_marca'],
                    'marca' => $row['marca_nombre']
                ],

                'mecanico' => [
                    'id_mecanico' => $row['id_mecanico'],
                    'id_user' => $row['id_user_mec'],
                    'nombre' => $row['mecanico_nombre'],
                    'apellidos' => $row['mecanico_apellidos']
                ],

                'estado' => $row['estado'],

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

    public function createCita($data) {
    try {
        $this->pdo->beginTransaction();

        // 1️⃣ Insertar cita
        $sqlCita = "INSERT INTO cita_taller (
                        id_user,
                        id_moto,
                        id_mecanico,
                        id_estado,
                        fecha_cita,
                        fecha_entrada,
                        observaciones
                    ) VALUES (
                        :id_user,
                        :id_moto,
                        :id_mecanico,
                        :id_estado,
                        :fecha_cita,
                        :fecha_entrada,
                        :observaciones
                    )";

        $stmt = $this->pdo->prepare($sqlCita);
        $stmt->execute([
            ':id_user' => $data['id_user'] ?? null,
            ':id_moto' => $data['id_moto'] ?? null,
            ':id_mecanico' => $data['id_mecanico'],
            ':id_estado' => $data['id_estado'] ?? 1, // Pendiente por defecto
            ':fecha_cita' => $data['fecha_cita'] ?? null,
            ':fecha_entrada' => $data['fecha_entrada'] ?? null,
            ':observaciones' => $data['observaciones'] ?? null
        ]);

        // 2️⃣ Obtener ID de la cita
        $id_cita = $this->pdo->lastInsertId();

        // 3️⃣ Insertar servicios
        if (!empty($data['servicios']) && is_array($data['servicios'])) {

            $sqlServicio = "INSERT INTO cita_servicios (
                                id_cita,
                                id_servicio,
                                precio_base,
                                descuento_aplicado,
                                precio_final
                            ) VALUES (
                                :id_cita,
                                :id_servicio,
                                :precio_base,
                                :descuento,
                                :precio_final
                            )";

            $stmtServicio = $this->pdo->prepare($sqlServicio);

            foreach ($data['servicios'] as $servicio) {

                $precio_base = $servicio['precio_base'];
                $descuento = $servicio['descuento'] ?? 0;

                // cálculo backend (muy importante no confiar en frontend)
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

        // 4️⃣ Commit
        $this->pdo->commit();

        return [
            'success' => true,
            'id_cita' => $id_cita
        ];

    } catch (Exception $e) {
        $this->pdo->rollBack();

        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
}