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
    $retorno=  obtenerMaterias();
    echo json_encode($retorno);
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $opcion = $data['opcion'];
  if($opcion == 'busca_una'){
    $carreraId = $data['carrera_id'];
    $anio_carrera = $data['anio_carrera'];
    if (!is_numeric($carreraId)) {
        
        echo json_encode($carreraId);
    }else {
        $materias =   obtenerMateriaPorCarreraYanio($carreraId,$anio_carrera);
        echo json_encode($materias);
    }
  
  }else   if($opcion == 'insertar'){
    $nombre= $data['nombre'];
    $horasCursada= $data['horasCursada']; 
    $formaAprobacion= $data['formaAprobacion'];
    $carreraId= $data['carreraId']; 
    $anioCarrera= $data['anio_carrera']; 
    $logico= $data['logico'];
    $retorno = insertarMateria($nombre, $horasCursada, $formaAprobacion, $carreraId, $anioCarrera, $logico);
    echo json_encode($retorno);
  }else   if($opcion == 'actualizar'){
    $materiaId = $data['id'];
    $nombre= $data['nombre'];
    $horasCursada= $data['horasCursada']; 
    $formaAprobacion= $data['formaAprobacion'];
    $carreraId= $data['carreraId']; 
    $anioCarrera= $data['anio_carrera']; 
    $logico= $data['logico'];
    $retorno = actualizarMateria($materiaId, $nombre, $horasCursada, $formaAprobacion, $carreraId, $anioCarrera, $logico);
    echo json_encode($retorno);
  }else   if($opcion == 'eliminar'){
    $materiaId = $data['id'];
    $logico = $data['logico'];
    $retorno= eliminarMateria($materiaId,$logico);
    echo json_encode($retorno);
  }

}
// Función para eliminado logico de una materia por su ID
function eliminarMateria($materiaId,$logico)
{
    $conexion = obtenerConexionBD();
    $query = "UPDATE materias
              SET logico = :logico
              WHERE id = :materia_id";
    $stmt =  $conexion ->prepare($query);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_STR);
    $stmt->bindParam(':materia_id', $materiaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Función para actualizar una materia por su ID
function actualizarMateria($materiaId, $nombre, $horasCursada, $formaAprobacion, $carreraId, $anioCarrera, $logico)
{
    $conexion = obtenerConexionBD();

    $query = "UPDATE materias
              SET nombre = :nombre, horas_cursada = :horas_cursada, forma_aprobacion = :forma_aprobacion,
                  carrera_id = :carrera_id, anio_carrera = :anio_carrera, logico = :logico
              WHERE id = :materia_id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':horas_cursada', $horasCursada, PDO::PARAM_INT);
    $stmt->bindParam(':forma_aprobacion', $formaAprobacion, PDO::PARAM_STR);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->bindParam(':anio_carrera', $anioCarrera, PDO::PARAM_INT);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materiaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

  // Función para insertar una nueva materia
function insertarMateria($nombre, $horasCursada, $formaAprobacion, $carreraId, $anioCarrera, $logico)
{
    $conexion = obtenerConexionBD();

    $query = "INSERT INTO materias (nombre, horas_cursada, forma_aprobacion, carrera_id, anio_carrera, logico)
              VALUES (:nombre, :horas_cursada, :forma_aprobacion, :carrera_id, :anio_carrera, :logico)";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':horas_cursada', $horasCursada, PDO::PARAM_INT);
    $stmt->bindParam(':forma_aprobacion', $formaAprobacion, PDO::PARAM_STR);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->bindParam(':anio_carrera', $anioCarrera, PDO::PARAM_INT);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);
    $stmt->execute();
    return $conexion->lastInsertId();
}
// Función para obtener una materias por su carrera y año
function obtenerMateriaPorCarreraYanio($carreraId, $anio_carrera)
{
    $conexion = obtenerConexionBD();

    $query = "SELECT * FROM materias WHERE carrera_id = :carrera_id AND anio_carrera = :anio_carrera";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT); // Corregimos el nombre del parámetro
    $stmt->bindParam(':anio_carrera', $anio_carrera, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Cambiamos fetch() por fetchAll()
}

  function obtenerMaterias()
{
    $conexion = obtenerConexionBD();

    $query = "SELECT materias.id as id, materias.nombre as nombre, materias.horas_cursada as horas_cursada,materias.forma_aprobacion as forma_aprobacion, materias.carrera_id as carrera_id, materias.anio_carrera as anio_carrera,
    materias.logico as logico, carreras.nombre as nombre_carrera FROM `materias` INNER JOIN carreras ON materias.carrera_id= carreras.id WHERE materias.logico = 0";
    $stmt = $conexion->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
