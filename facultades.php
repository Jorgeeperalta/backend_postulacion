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
    $estudianteId = $data['id_estudiante'];
    $materiaId = $data['id_materia'];
    $inscripcionId = $data['id_inscripcion'];
    $retorno= actualizarInscripcion($inscripcionId, $estudianteId, $materiaId);
    echo json_encode($retorno);
  }else if($opcion == 'eliminar'){
    $facultadId = $data['id'];
    $retorno=eliminarFacultad($facultadId);
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
function eliminarFacultad($facultadId)
{
    $conexion = obtenerConexionBD();

    $query = "DELETE FROM facultades WHERE id_facultad = :facultad_id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':facultad_id', $facultadId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount();
}

// Función para actualizar una inscripción por su ID
function actualizarInscripcion($inscripcionId, $estudianteId, $materiaId)
{
    $conexion = obtenerConexionBD();

    $query = "UPDATE inscripciones
              SET estudiante_id = :estudiante_id,
                  materia_id = :materia_id
              WHERE id = :inscripcion_id";
              
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materiaId, PDO::PARAM_INT);
    $stmt->bindParam(':inscripcion_id', $inscripcionId, PDO::PARAM_INT);

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

