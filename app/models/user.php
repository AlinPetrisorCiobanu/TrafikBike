<?php
require_once __DIR__ . '/../core/database.php';

class User {

    private $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
    }

    // Login
    public function login($user_login, $pass){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE usuario=:login OR email=:login Limit 1");
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
                (nombre, apellidos, dni, telefono, email, usuario, password_hash, descuento, is_active, confirmed, id_role)
                VALUES (:nombre, :apellidos, :dni, :telefono, :email, :usuario, :password_hash, 0, 1, 0, :id_role)
            ");

            $stmt->execute([
                'nombre'        => $data['nombre'],
                'apellidos'     => $data['apellidos'],
                'dni'           => $data['dni'],
                'telefono'      => $data['telefono'],
                'email'         => $data['email'],
                'usuario'       => $data['usuario'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'id_role'       => $data['id_role'] ?? 3
            ]);

            // Devolver true si se insertó correctamente
            return true;

        } catch(PDOException $e) {
            // Si hay error, devolver false
            return false;
        }
    }
}