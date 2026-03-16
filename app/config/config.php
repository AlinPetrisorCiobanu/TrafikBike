<?php
class Database {
    private static $instance = null;
    public $pdo;

    private function __construct() {
        $config = json_decode(file_get_contents(__DIR__ . '/../private/private.json'));

        try {
            $this->pdo = new PDO(
                "mysql:host={$config->host};charset=utf8mb4",
                $config->user,
                $config->pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->pdo->exec("USE {$config->name}");

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}
?>