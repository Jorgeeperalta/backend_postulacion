<?php

require_once 'db1.php';
//require_once 'db.php';


// Allow from any origin
if (isset($_SERVER["HTTP_ORIGIN"])) {
    // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to allow, or as we do here, just allow all
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
} else {
    //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");    // cache for 10 minutes

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //Just exit with 200 OK with the above headers for OPTIONS method
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $opcion = $data['opcion'];
  if($opcion == 'insertar'){
    $nombreFacultad = $data['nombreFacultad'];
    $logico = $data['logico'];
   
    $retorno= insertarFacultad($nombreFacultad, $logico);
    echo json_encode($retorno);
  }else if($opcion == 'actualizar'){
    $facultadId = $data['id_facultad'];
    $nombreFacultad = $data['nombre_facultad'];
    $logico = $data['logico'];
    $retorno= editarFacultad($facultadId, $nombreFacultad, $logico);
    echo json_encode($retorno);
  }else if($opcion == 'eliminar'){
    $facultadId = $data['id'];
    $logico = $data['logico'];
    $retorno=eliminarFacultad($facultadId,$logico);
    echo json_encode($retorno);
  }else if($opcion == 'obtener'){
    $retorno=obtenerFacultades();
    echo json_encode($retorno);
  }

}
// Función para obtener todas las facultades
function obtenerFacultades()
{
    $conexion = obtenerConexionBD();

    $query = "SELECT * FROM facultades";
    $stmt = $conexion->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para eliminar una facultad por su ID
function eliminarFacultad($facultadId,$logico)
{
    $conexion = obtenerConexionBD();

    $query = "UPDATE facultades
              SET  logico = :logico
              WHERE id_facultad = :facultad_id";
              
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);
    $stmt->bindParam(':facultad_id', $facultadId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
}

function editarFacultad($facultadId, $nombreFacultad, $logico)
{
    $conexion = obtenerConexionBD();

    $query = "UPDATE facultades
              SET nombre_facultad = :nombre_facultad, logico = :logico
              WHERE id_facultad = :facultad_id";
              
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':nombre_facultad', $nombreFacultad, PDO::PARAM_STR);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);
    $stmt->bindParam(':facultad_id', $facultadId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
}
// Función para insertar una nueva facultad
function insertarFacultad($nombreFacultad, $logico)
{
    $conexion = obtenerConexionBD();

    $query = "INSERT INTO facultades (nombre_facultad, logico)
              VALUES (:nombre_facultad, :logico)";
              
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':nombre_facultad', $nombreFacultad, PDO::PARAM_STR);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);

    $stmt->execute();

    return $conexion->lastInsertId();
}

