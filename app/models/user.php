<?php
require_once __DIR__ . '/../core/database.php';

class User {

    private $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
    }

    // Login
    public function login($user_login, $pass){
        $stmt = $this->pdo->prepare("
            SELECT users.*,role.nombre AS rol FROM users
            INNER JOIN role USING(id_role)
            WHERE usuario=:login OR email=:login Limit 1"
        );
        $stmt->execute(['login'=>$user_login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($pass, $user['password_hash'])){
            return $user;
        }

        return false;
    }

    // Registrar usuario
    public function register($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO users
                (nombre, apellidos, dni, telefono, email, usuario, password_hash, id_role)
                VALUES (:nombre, :apellidos, :dni, :telefono, :email, :usuario, :password_hash, :id_role)
            ");

            $stmt->execute([
                'nombre'        => $data['nombre'],
                'apellidos'     => $data['apellidos'],
                'dni'           => $data['dni'],
                'telefono'      => $data['telefono'],
                'email'         => $data['email'],
                'usuario'       => $data['usuario'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'id_role'       => $data['id_role'] ?? 7
            ]);

            // 2. Recuperar el usuario recién creado
            $userId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("
                SELECT u.*, r.nombre AS rol,
                    v.comision, v.sueldo AS sueldo_vendedor, v.fecha_contrato AS fecha_contrato_vendedor,
                    m.cargo, m.sueldo AS sueldo_mecanico, m.fecha_contrato AS fecha_contrato_mecanico
                FROM users u
                LEFT JOIN role r ON u.id_role = r.id_role
                LEFT JOIN vendedor v ON u.id_user = v.id_user
                LEFT JOIN mecanico m ON u.id_user = m.id_user
                WHERE u.id_user = :id_user
                LIMIT 1
            ");

            $stmt->execute(['id_user' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user;

        } catch(PDOException $e) {
            die("Error al registrar usuario: " . $e->getMessage());
        }
    }

        // Obtener todos los usuarios de la base de datos
    public function getAllUsers() {
    $stmt = $this->pdo->prepare("
        SELECT 
            u.*,
            r.nombre AS rol,
            v.comision, v.sueldo AS sueldo_vendedor, v.fecha_contrato AS fecha_contrato_vendedor,
            m.cargo, m.sueldo AS sueldo_mecanico, m.fecha_contrato AS fecha_contrato_mecanico
        FROM users u
        LEFT JOIN role r ON u.id_role = r.id_role
        LEFT JOIN vendedor v ON u.id_user = v.id_user
        LEFT JOIN mecanico m ON u.id_user = m.id_user
        ORDER BY u.id_user DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por el ID
    public function getUserById($id_user) {
    $stmt = $this->pdo->prepare("
        SELECT 
            u.*,
            r.nombre AS rol,
            v.comision, v.sueldo AS sueldo_vendedor, v.fecha_contrato AS fecha_contrato_vendedor,
            m.cargo, m.sueldo AS sueldo_mecanico, m.fecha_contrato AS fecha_contrato_mecanico
        FROM users u
        LEFT JOIN role r ON u.id_role = r.id_role
        LEFT JOIN vendedor v ON u.id_user = v.id_user
        LEFT JOIN mecanico m ON u.id_user = m.id_user
        WHERE u.id_user = :id_user
        LIMIT 1
    ");

    $stmt->execute(['id_user' => $id_user]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id_user, $data) {
    // Campos de la tabla users
    $fieldsUser = [];
    $paramsUser = ['id_user' => $id_user];

    $mapUser = ['nombre','apellidos','dni','telefono','email','usuario','id_role'];
    foreach ($mapUser as $field) {
        if (isset($data[$field])) {
            $fieldsUser[] = "$field=:$field";
            $paramsUser[$field] = $data[$field];
        }
    }

    // Contraseña
    if (!empty($data['password'])) {
        $fieldsUser[] = "password_hash=:password_hash";
        $paramsUser['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    // Ejecutar actualización de users
    if (count($fieldsUser) > 0) {
        $sqlUser = "UPDATE users SET " . implode(', ', $fieldsUser) . " WHERE id_user=:id_user";
        $stmt = $this->pdo->prepare($sqlUser);
        $stmt->execute($paramsUser);
    }

    // Campos de vendedor
    if (isset($data['comision']) || isset($data['sueldo_vendedor']) || isset($data['fecha_contrato_vendedor'])) {
        $fieldsVend = [];
        $paramsVend = ['id_user' => $id_user];

        if (isset($data['comision'])) { $fieldsVend[] = "comision=:comision"; $paramsVend['comision'] = $data['comision']; }
        if (isset($data['sueldo_vendedor'])) { $fieldsVend[] = "sueldo=:sueldo"; $paramsVend['sueldo'] = $data['sueldo_vendedor']; }
        if (isset($data['fecha_contrato_vendedor'])) { $fieldsVend[] = "fecha_contrato=:fecha_contrato"; $paramsVend['fecha_contrato'] = $data['fecha_contrato_vendedor']; }

        if (count($fieldsVend) > 0) {
            $sqlVend = "UPDATE vendedor SET " . implode(', ', $fieldsVend) . " WHERE id_user=:id_user";
            $stmt = $this->pdo->prepare($sqlVend);
            $stmt->execute($paramsVend);
        }
    }

    // Campos de mecanico
    if (isset($data['cargo']) || isset($data['sueldo_mecanico']) || isset($data['fecha_contrato_mecanico'])) {
        $fieldsMec = [];
        $paramsMec = ['id_user' => $id_user];

        if (isset($data['cargo'])) { $fieldsMec[] = "cargo=:cargo"; $paramsMec['cargo'] = $data['cargo']; }
        if (isset($data['sueldo_mecanico'])) { $fieldsMec[] = "sueldo=:sueldo"; $paramsMec['sueldo'] = $data['sueldo_mecanico']; }
        if (isset($data['fecha_contrato_mecanico'])) { $fieldsMec[] = "fecha_contrato=:fecha_contrato"; $paramsMec['fecha_contrato'] = $data['fecha_contrato_mecanico']; }

        if (count($fieldsMec) > 0) {
            $sqlMec = "UPDATE mecanico SET " . implode(', ', $fieldsMec) . " WHERE id_user=:id_user";
            $stmt = $this->pdo->prepare($sqlMec);
            $stmt->execute($paramsMec);
        }
    }

    // Devolver el usuario actualizado
    return $this->getUserById($id_user);
}
}