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
  if($opcion == 'busca_por_inscripcion'){
    $inscripcionId = $data['inscripcion_id'];
    if (!is_numeric($inscripcionId)) {
        
        echo json_encode( $inscripcionId);
    }else {
        $notas =obtenerNotasPorInscripcionId($inscripcionId);
        echo json_encode($notas);
    }
  }else  if($opcion === 'insertar'){
    
    $inscripcionId = $data['inscripcion_id'];
    
    $parcial1 = $data['parcial1'];
    $parcial2 = $data['parcial2'];
    $parcial3= $data['parcial3'];
    $parcial4 = $data['parcial4'];
    $final = $data['final'];
   // echo json_encode($data);
    $retorno= insertarNota($inscripcionId, $parcial1, $parcial2, $parcial3, $parcial4, $final);
    echo json_encode($retorno);
  }else  if($opcion === 'obtenerNotasPorEstudiante'){
     $estudianteId = $data['estudiante_id'];
     $retorno=obtenerNotasPorEstudiante($estudianteId);
     echo json_encode($retorno);
  }

}
function obtenerNotasPorEstudiante($estudianteId)
{
    $conexion = obtenerConexionBD();
   
    $query = "SELECT * FROM estudiantes
              INNER JOIN inscripciones ON estudiantes.id = inscripciones.estudiante_id
              INNER JOIN materias ON materias.id = inscripciones.materia_id
              INNER JOIN notas ON notas.inscripcion_id = inscripciones.id
              WHERE estudiantes.id = :estudiante_id";
    
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para insertar una nueva nota
function insertarNota($inscripcionId, $parcial1, $parcial2, $parcial3, $parcial4, $final)
{
    
    $conexion = obtenerConexionBD();
    $notasExistentes = obtenerNotasPorInscripcionId($inscripcionId);
    if ($notasExistentes) {
        $query = "UPDATE notas
        SET parcial_1 = :parcial_1,
            parcial_2 = :parcial_2,
            parcial_3 = :parcial_3,
            parcial_4 = :parcial_4,
            final = :final
        WHERE inscripcion_id = :inscripcion_id";
        
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':parcial_1', $parcial1, PDO::PARAM_STR);
        $stmt->bindParam(':parcial_2', $parcial2, PDO::PARAM_STR);
        $stmt->bindParam(':parcial_3', $parcial3, PDO::PARAM_STR);
        $stmt->bindParam(':parcial_4', $parcial4, PDO::PARAM_STR);
        $stmt->bindParam(':final', $final, PDO::PARAM_STR);
        $stmt->bindParam(':inscripcion_id', $inscripcionId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }else{
        $query = "INSERT INTO notas ( `inscripcion_id`, `parcial_1`, `parcial_2`, `parcial_3`, `parcial_4`, `final`) 
        VALUES (:inscripcion_id, :parcial_1, :parcial_2, :parcial_3, :parcial_4, :final)";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':inscripcion_id',$inscripcionId, PDO::PARAM_INT);
        $stmt->bindParam(':parcial_1', $parcial1, PDO::PARAM_STR);
        $stmt->bindParam(':parcial_2', $parcial2, PDO::PARAM_STR);
        $stmt->bindParam(':parcial_3', $parcial3, PDO::PARAM_STR);
        $stmt->bindParam(':parcial_4', $parcial4, PDO::PARAM_STR);
        $stmt->bindParam(':final', $final, PDO::PARAM_STR);
        $stmt->execute();
        return $conexion->lastInsertId();
    }

   
}

// Función para obtener las notas por el ID de inscripción
function obtenerNotasPorInscripcionId($inscripcionId)
{
    $conexion = obtenerConexionBD();

    $query = "SELECT * FROM notas WHERE inscripcion_id = :inscripcion_id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':inscripcion_id', $inscripcionId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
