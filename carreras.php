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




if  ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql = "SELECT * FROM carreras";
    $results = dbQuery($sql);
    $rows = array();

	while($row = dbFetchAssoc($results)) {
		$rows[] = $row;
	}

	echo json_encode($rows);


}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $opcion = $data['opcion'];
  if($opcion == 'busca_una'){
    $carreraId = $data['pk_carrera'];
    if (!is_numeric($carreraId)) {
        
        echo json_encode( $carreraId);
    }else {
        $carrera = buscarCarrera($carreraId);
        echo json_encode($carrera);
    }
  }else   if($opcion == 'insertar'){
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $logico = $data['logico'];
    $fecha_apertura= $data['fecha_apertura'];
    $id_facultad = $data['id_facultad'];
    $anios_cursada = $data['anios_cursada'];
    $retorno= insertarCarrera($nombre, $descripcion,$logico, $fecha_apertura, $id_facultad, $anios_cursada);
    echo json_encode($retorno);
  }else   if($opcion == 'actualizar'){
    $carreraId = $data['id'];
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $logico = $data['logico'];
    $fecha_apertura= $data['fecha_apertura'];
    $id_facultad = $data['id_facultad'];
    $anios_cursada = $data['anios_cursada'];
    $retorno= actualizarCarrera($carreraId,$nombre, $descripcion,$logico, $fecha_apertura, $id_facultad, $anios_cursada);
    echo json_encode($retorno);
  }else   if($opcion == 'eliminar'){
    $carreraId = $data['id'];
    $logico = $data['logico'];
    $retorno= eliminarCarrera($carreraId,$logico);
    echo json_encode($retorno);
  }
    
    
    


}
// Función para eliminado logico de una carrera por su ID
function eliminarCarrera($carreraId,$logico)
{
    $conexion = obtenerConexionBD();
    $query = "UPDATE Carreras
              SET logico = :logico
              WHERE id = :carrera_id";
    $stmt =  $conexion ->prepare($query);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_STR);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Función para actualizar una carrera por su ID
function actualizarCarrera($carreraId, $nombre, $descripcion,$logico, $fecha_apertura, $id_facultad, $anios_cursada)
{
    $conexion = obtenerConexionBD();
    $query = "UPDATE Carreras
              SET nombre = :nombre, descripcion = :descripcion, logico = :logico,fecha_apertura = :fecha_apertura, id_facultad = :id_facultad, anios_cursada = :anios_cursada
              WHERE id = :carrera_id";
    $stmt =  $conexion ->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_apertura', $fecha_apertura, PDO::PARAM_STR);
    $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
    $stmt->bindParam(':anios_cursada', $anios_cursada, PDO::PARAM_INT);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Función para obtener los datos de una carrera por su ID
function buscarCarrera($carreraId)
{
    $conexion = obtenerConexionBD();

    $query = "SELECT * FROM carreras WHERE id = :carrera_id";
    $stmt = $conexion->prepare($query);
    $stmt->bindValue(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();

   $carrera = $stmt->fetch(PDO::FETCH_ASSOC);

    return$carrera;
}

function insertarCarrera($nombre, $descripcion, $logico, $fecha_apertura, $id_facultad, $anios_cursada)
{
    $conexion = obtenerConexionBD();

    $query = "INSERT INTO carreras (nombre, descripcion, logico, fecha_apertura, id_facultad, anios_cursada) 
              VALUES (:nombre, :descripcion, :logico, :fecha_apertura, :id_facultad, :anios_cursada)";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_apertura', $fecha_apertura, PDO::PARAM_STR);
    $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
    $stmt->bindParam(':anios_cursada', $anios_cursada, PDO::PARAM_INT);
    $stmt->execute();

    return $conexion->lastInsertId();
}

