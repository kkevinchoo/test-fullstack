<?php
    //Conectarse con BD
    function connectBD(){
        try {
            //Editar según configuración local
            $host = 'localhost';
            $dbname = 'prueba';
            $user = 'postgres';
            $password = 'kkevinchoo';

            $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }
?>