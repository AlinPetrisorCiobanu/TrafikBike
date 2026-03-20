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
}