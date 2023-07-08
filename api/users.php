<?php
include "../utils/connect2bd.php";
include "../utils/todo.php";
$pdo = connectBD();

// Función para crear un nuevo usuario
function crearUsuario($username, $password)
{
    $username = cleanInput($username);
    $password = cleanInput($password);

    try {
        $pdo = connectBD();
        $sql = "INSERT INTO Users (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        echo "Usuario creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear el usuario: " . $e->getMessage();
    }
}

function obtenerUsuarios()
{
    try {
        $pdo = connectBD();
        $sql = "SELECT * FROM Users";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } catch (PDOException $e) {
        echo "Error al obtener los usuarios: " . $e->getMessage();
    }
}

function validarCredenciales($username, $password)
{
    global $pdo;
    $username = cleanInput($username);
    $password = cleanInput($password);
    //$password = encryptPass($password);

    $sql = "SELECT COUNT(*) AS count FROM Users WHERE username = :username AND password = :password";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    //var_dump($resultado['count']);
    if ($resultado['count'] > 0) {
        echo "true";
    } else {
        echo "false";
    }
}

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'crear':
            if (isset($_POST['username']) && isset($_POST['password'])) {
                crearUsuario($_POST['username'], $_POST['password']);
            }
            break;
        case 'obtener':
            obtenerUsuarios();
            break;
        case 'login':
            if (isset($_POST['username']) && isset($_POST['password'])) {
                //echo "true";
                validarCredenciales($_POST['username'], $_POST['password']);
            }else{
                echo "false";
            }
            break;
        default:
            echo "Acción no válida.";
            break;
    }
}
