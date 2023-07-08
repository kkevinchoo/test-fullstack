<?php

include "../utils/connect2bd.php";
include "../utils/todo.php";
$pdo = connectBD();

function crearVuelo($date, $time_start, $time_end, $duration, $cost, $id_type, $id_user) {
    $date = cleanInput($date);
    $time_start = cleanInput($time_start);
    $time_end = cleanInput($time_end);
    $duration = cleanInput($duration);
    $cost = cleanInput($cost);
    $id_type = cleanInput($id_type);
    $id_user = cleanInput($id_user);

    try {
        $pdo = connectBD();
        $sql = "INSERT INTO Flights (date_flight, time_start, time_end, duration, cost, id_type, id_user) VALUES (:date, :time_start, :time_end, :duration, :cost, :id_type, :id_user)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time_start', $time_start);
        $stmt->bindParam(':time_end', $time_end);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':cost', $cost);
        $stmt->bindParam(':id_type', $id_type);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        echo "Vuelo creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear el vuelo: " . $e->getMessage();
    }
}

function obtenerVuelos() {
    try {
        $pdo = connectBD();
        $sql = "SELECT flights.*,types.name,users.username FROM Flights, Types, Users WHERE types.id= flights.id_type AND users.id = flights.id_user;";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } catch (PDOException $e) {
        echo "Error al obtener los vuelos: " . $e->getMessage();
    }
}

function actualizarVuelo($id, $date, $time_start, $time_end, $duration, $cost, $id_type, $id_user) {
    $id = cleanInput($id);
    $date = cleanInput($date);
    $time_start = cleanInput($time_start);
    $time_end = cleanInput($time_end);
    $duration = cleanInput($duration);
    $cost = cleanInput($cost);
    $id_type = cleanInput($id_type);
    $id_user = cleanInput($id_user);

    try {
        $pdo = connectBD();
        $sql = "UPDATE Flights SET date_flight = :date, time_start = :time_start, time_end = :time_end, duration = :duration, cost = :cost, id_type = :id_type, id_user = :id_user WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time_start', $time_start);
        $stmt->bindParam(':time_end', $time_end);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':cost', $cost);
        $stmt->bindParam(':id_type', $id_type);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        var_dump($stmt);
    } catch (PDOException $e) {
        echo "Error al actualizar el vuelo: " . $e->getMessage();
    }
}

function eliminarVuelo($id) {
    $id = cleanInput($id);

    try {
        $pdo = connectBD();
        $sql = "DELETE FROM Flights WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo "Vuelo eliminado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar el vuelo: " . $e->getMessage();
    }
}

function filtrarVuelos()
{
  global $pdo;

  $fecha = cleanInput(isset($_POST['fecha']) ? $_POST['fecha'] : '');
  $hora = cleanInput(isset($_POST['hora']) ? $_POST['hora'] : '');
  $tipo = cleanInput(isset($_POST['tipo']) ? $_POST['tipo'] : '');
  $costo = cleanInput(isset($_POST['costo']) ? $_POST['costo'] : '');

  $sql = "SELECT f.id, f.date_flight, f.time_start, f.time_end, f.duration, f.cost, t.name AS type_name, u.username
          FROM Flights f
          INNER JOIN Types t ON f.id_type = t.id
          INNER JOIN Users u ON f.id_user = u.id
          WHERE 1 = 1";

  if (!empty($fecha)) {$sql .= " AND f.date_flight = :fecha";}
  if (!empty($hora)) {$sql .= " AND f.time_start = :hora";}
  if (!empty($tipo)) {$sql .= " AND f.id_type = :tipo";}
  if (!empty($costo)) {$sql .= " AND f.cost = :costo";}

  // Preparar la consulta
  $stmt = $pdo->prepare($sql);
  if (!empty($fecha)) {$stmt->bindParam(':fecha', $fecha);}
  if (!empty($hora)) {$stmt->bindParam(':hora', $hora);}
  if (!empty($tipo)) {$stmt->bindParam(':tipo', $tipo);}
  if (!empty($costo)) {$stmt->bindParam(':costo', $costo);}
  $stmt->execute();
  $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($resultados);
}

function obtenerVuelo($id)
{
  global $pdo;

  $id = cleanInput($id);
  $sql = "SELECT f.id, f.date_flight, f.time_start, f.time_end, f.id_type, f.duration, f.cost
          FROM Flights f
          WHERE f.id = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  echo json_encode($result);
}

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'crear':
            if (isset($_POST['date'], $_POST['time_start'], $_POST['time_end'], $_POST['duration'], $_POST['cost'], $_POST['id_type'], $_POST['id_user'])) {
                crearVuelo($_POST['date'], $_POST['time_start'], $_POST['time_end'], $_POST['duration'], $_POST['cost'], $_POST['id_type'], $_POST['id_user']);
            }
            break;
        case 'obtener':
            obtenerVuelos();
            break;
        case 'actualizar':
            if (isset($_POST['id'], $_POST['date'], $_POST['time_start'], $_POST['time_end'], $_POST['duration'], $_POST['cost'], $_POST['id_type'], $_POST['id_user'])) {
                actualizarVuelo($_POST['id'], $_POST['date'], $_POST['time_start'], $_POST['time_end'], $_POST['duration'], $_POST['cost'], $_POST['id_type'], $_POST['id_user']);
            }
            break;
        case 'eliminar':
            if (isset($_POST['id'])) {
                eliminarVuelo($_POST['id']);
            }
            break;
        case 'filtrar':
            filtrarVuelos();
            break;
        case 'obtenerVuelo':
            if (isset($_POST['id'])) {
                obtenerVuelo($_POST['id']);
            }
            break;
        default:
            echo "Acción no válida.";
            break;
    }
}
