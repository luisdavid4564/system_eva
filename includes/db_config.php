<?php
class Conn {
    public $dbh;

    public function Conexion() {
        try {
            $host = "localhost";
            $dbname = "eva"; 
            $username = "root"; 
            $password = "14152108.luis"; 

            $conn = $this->dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch (Exception $e) {
            echo "Error de conexión a la BD: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}
?>
