<?php
    include "../utils/connect2bd.php";
    include "../utils/todo.php";
    $pdo = connectBD();

    function createType($name) {
        $name = cleanInput($name);

        try {
            $pdo = connectBD();
            $sql = "INSERT INTO Types (name) VALUES (:name)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            echo "Registro creado exitosamente.";
        } catch (PDOException $e) {
            echo "Error al crear el registro: " . $e->getMessage();
        }
    }

    function getTypes() {
        try {
            $pdo = connectBD();
            $sql = "SELECT * FROM Types";
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results);
        } catch (PDOException $e) {
            echo "Error al obtener los registros: " . $e->getMessage();
        }
    }

    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'crear':
                if (isset($_POST['name'])) {
                    createType($_POST['name']);
                }
                break;
            case 'obtener':
                getTypes();
                break;
            default:
                echo "Acción no válida.";
                break;
        }
    }
