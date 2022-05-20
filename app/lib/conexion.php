<?php
require_once __DIR__ . '/../config/config.php';

class conexion {
    protected $pdo;

    public function __construct(){
       
    }
    
    public function connect(){
        try {
            $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException  $e) {
            echo 'Error al conectar con la base de datos!<br> ' . $e -> getMessage();
            die();
        }
        return $this->pdo;
    }
    
    public function __destruct() {
        // close db connection 
        $this->pdo = null;
    }
}
?>