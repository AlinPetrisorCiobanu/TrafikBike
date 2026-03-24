<?php
require_once __DIR__ . '/../core/database.php';

class Workers {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo;
    }

    /**
     * Añade un usuario y su perfil (mecánico o vendedor)
     * @param string $tipo 'mecanico' o 'vendedor'
     * @param string $nombre
     * @param string $apellidos
     * @param string $dni
     * @param string|null $telefono
     * @param string $email
     * @param string $usuario
     * @param string $password_hash
     * @param float $sueldo
     * @param string|float|null $extra Cargo para mecánico / Comisión para vendedor
     * @return int ID del usuario creado
     * @throws Exception
     */
    public function addUser($tipo, $nombre, $apellidos, $dni, $telefono, $email, $usuario, $password_hash, $sueldo, $extra = null) {

        $rolesMap = [
            'vendedor' => 4,
            'mecanico' => 5 
        ];

        $tipoLower = strtolower($tipo);
        $id_role = $rolesMap[$tipoLower] ?? null;

        if (!$id_role) {
            throw new Exception("El rol '$tipo' no existe.");
        }

        try {
            $this->pdo->beginTransaction();

            // Validar que DNI, email y usuario no existan
            $stmtCheck = $this->pdo->prepare("
                SELECT COUNT(*) FROM users 
                WHERE dni = :dni OR email = :email OR usuario = :usuario
            ");
            $stmtCheck->execute([
                'dni' => $dni,
                'email' => $email,
                'usuario' => $usuario
            ]);

            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("DNI, email o usuario ya existe en la base de datos.");
            }

            // Insertar usuario
            $stmtUser = $this->pdo->prepare("
                INSERT INTO users (id_role, nombre, apellidos, dni, telefono, email, usuario, password_hash)
                VALUES (:id_role, :nombre, :apellidos, :dni, :telefono, :email, :usuario, :password_hash)
            ");
            $stmtUser->execute([
                'id_role' => $id_role,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'dni' => $dni,
                'telefono' => $telefono,
                'email' => $email,
                'usuario' => $usuario,
                'password_hash' => $password_hash
            ]);

            $id_user = $this->pdo->lastInsertId();

            // Insertar en tabla específica
            if ($tipoLower === 'mecanico') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO mecanico (id_user, cargo, sueldo, fecha_contrato)
                    VALUES (:id_user, :cargo, :sueldo, NOW())
                ");
                $stmt->execute([
                    'id_user' => $id_user,
                    'cargo' => $extra ?? 'Mecánico',
                    'sueldo' => $sueldo
                ]);
            } elseif ($tipoLower === 'vendedor') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO vendedor (id_user, sueldo, comision, fecha_contrato)
                    VALUES (:id_user, :sueldo, :comision, NOW())
                ");
                $stmt->execute([
                    'id_user' => $id_user,
                    'sueldo' => $sueldo,
                    'comision' => $extra ?? 0.0
                ]);
            } else {
                throw new Exception("Tipo de usuario inválido. Debe ser 'mecanico' o 'vendedor'.");
            }

            $this->pdo->commit();
            return $id_user;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}