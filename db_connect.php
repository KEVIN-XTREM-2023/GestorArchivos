<?php

class Database {
    private static $instance;
    private $conn;
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'filesystem';

    private function __construct() {
        // Crear conexión
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        // Verificar conexión
        if ($this->conn->connect_error) {
            die("No se pudo conectar a MySQL: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

/* // Obtener la instancia de la conexión
$db = Database::getInstance();
$conn = $db->getConnection();


$db->closeConnection(); */
?>
