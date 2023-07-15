<?php

require_once 'db1.php';
require_once 'db.php';


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




if  ($_SERVER['REQUEST_METHOD'] === 'GET') {
// Función para obtener los datos de un estudiante por su ID

    $data = json_decode(file_get_contents("php://input", true));
    $sql = "SELECT * FROM estudiantes";
    $results = dbQuery($sql);
    $rows = array();

	while($row = dbFetchAssoc($results)) {
		$rows[] = $row;
	}

	echo json_encode($rows);
    // $query = "SELECT * FROM Estudiantes WHERE id = :estudiante_id";
    // $stmt = $pdo->prepare($query);
    // $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    // $stmt->execute();
    // return $stmt->fetch(PDO::FETCH_ASSOC);


}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $estudianteId = $data['pk_estudiante'];
    if (!is_numeric($estudianteId)) {
        
        echo json_encode("El ID del estudiante debe ser un número entero.");
    }else {
    $estudiante = buscarEstudiante($estudianteId);
    echo json_encode($estudiante);
    }
}

// Función para obtener los datos de un estudiante por su ID
function buscarEstudiante($estudianteId)
{
    $conexion = obtenerConexionBD();

    $query = "SELECT * FROM estudiantes WHERE id = :estudiante_id";
    $stmt = $conexion->prepare($query);
    $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->execute();

    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    return $estudiante;
}
