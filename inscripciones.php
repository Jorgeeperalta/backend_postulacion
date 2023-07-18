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
    $estudianteId = $data['id_estudiante'];
    $materiaId = $data['id_materia'];
   
    $retorno= insertarInscripcion($estudianteId, $materiaId);
    echo json_encode($retorno);
  }else if($opcion == 'actualizar'){
    $estudianteId = $data['id_estudiante'];
    $materiaId = $data['id_materia'];
    $inscripcionId = $data['id_inscripcion'];
    $retorno= actualizarInscripcion($inscripcionId, $estudianteId, $materiaId);
    echo json_encode($retorno);
  }
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

// Función para insertar una nueva inscripción
function insertarInscripcion($estudianteId, $materiaId)
{
    $conexion = obtenerConexionBD();

    $query = "INSERT INTO inscripciones (estudiante_id, materia_id)
              VALUES (:estudiante_id, :materia_id)";
              
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materiaId, PDO::PARAM_INT);

    $stmt->execute();

    return $conexion->lastInsertId();
}
