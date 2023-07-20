<?php

require_once 'db1.php';



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

    $estudiantes =obtenerTodosEstudiantes();
    echo json_encode($estudiantes);


}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $opcion = $data['opcion'];
    if($opcion == 'busca_uno'){
      
       $dni = $data['dni'];
    if (!is_numeric($dni)) {
        
        echo json_encode("El ID del estudiante debe ser un número entero.");
    }else {
    $estudiante = buscarEstudiante($dni);
    echo json_encode($estudiante);
    }
}else if($opcion == 'insertar'){
    $dni = $data['dni'];
    $apellidoNombre = $data['apellido_nombre'];
    $celular = $data['celular'];
    $mail = $data['mail']; 
    $edad = $data['edad'];
    $codigoPostal = $data['codigo_postal'];
    $domicilio = $data['domicilio'];
    $logico = $data['logico'];
    $carreraId = $data['carrera_id'];
   $retorna= insertarEstudiante($dni, $apellidoNombre, $celular, $mail, $edad, $codigoPostal, $domicilio,$logico, $carreraId);
   echo json_encode($retorna);
} else if($opcion == 'actualizar'){
    $estudianteId= $data['id'];
    $dni = $data['dni'];
    $apellidoNombre = $data['apellido_nombre'];
    $celular = $data['celular'];
    $mail = $data['mail']; 
    $edad = $data['edad'];
    $codigoPostal = $data['codigo_postal'];
    $domicilio = $data['domicilio'];
    $logico = $data['logico'];
    $carreraId = $data['carrera_id'];
    $retorna= actualizarEstudiante($estudianteId, $dni, $apellidoNombre, $celular, $mail, $edad, $codigoPostal, $domicilio, $logico, $carreraId);
    echo json_encode($retorna);
}else   if($opcion == 'eliminar'){
    $estudianteId = $data['id'];
    $logico = $data['logico'];
    $retorno= eliminarEstudiante($estudianteId, $logico);
    echo json_encode($retorno);
  }
}
// Función para obtener todos los estudiantes
function obtenerTodosEstudiantes()
{
    $conexion = obtenerConexionBD();

    $query = "SELECT estudiantes.dni as dni, estudiantes.apellido_nombre as apellido_nombre, estudiantes.celular as celular,
    estudiantes.mail as mail, estudiantes.edad as edad, estudiantes.codigo_postal as codigo_postal, estudiantes.domicilio AS
    domicilio, estudiantes.carrera_id as carrera_id, carreras.nombre as carrera_nombre
    FROM `estudiantes` INNER JOIN carreras ON estudiantes.carrera_id = carreras.id WHERE estudiantes.logico = 0";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function eliminarEstudiante($estudianteId, $logico)
{
    $conexion = obtenerConexionBD();
    $query = "UPDATE estudiantes
              SET logico = :logico
              WHERE id = :estudiante_id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}
// Función para actualizar un estudiante por su ID
function actualizarEstudiante($estudianteId, $dni, $apellidoNombre, $celular, $mail, $edad, $codigoPostal, $domicilio, $logico, $carreraId)
{
    $conexion = obtenerConexionBD();
    $query = "UPDATE Estudiantes
              SET dni = :dni,
                  apellido_nombre = :apellido_nombre,
                  celular = :celular,
                  mail = :mail,
                  edad = :edad,
                  codigo_postal = :codigo_postal,
                  domicilio = :domicilio,
                  logico = :logico,
                  carrera_id = :carrera_id
              WHERE id = :estudiante_id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
    $stmt->bindParam(':apellido_nombre', $apellidoNombre, PDO::PARAM_STR);
    $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':codigo_postal', $codigoPostal, PDO::PARAM_STR);
    $stmt->bindParam(':domicilio', $domicilio, PDO::PARAM_STR);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
}

// Función para insertar un estudiante
function insertarEstudiante($dni, $apellidoNombre, $celular, $mail, $edad, $codigoPostal, $domicilio,$logico, $carreraId)
{
    $conexion = obtenerConexionBD();
    $query = "INSERT INTO Estudiantes (dni, apellido_nombre, celular, mail, edad, codigo_postal, domicilio,logico, carrera_id)
              VALUES (:dni, :apellido_nombre, :celular, :mail, :edad, :codigo_postal, :domicilio, :logico, :carrera_id)";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
    $stmt->bindParam(':apellido_nombre', $apellidoNombre, PDO::PARAM_STR);
    $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':codigo_postal', $codigoPostal, PDO::PARAM_STR);
    $stmt->bindParam(':domicilio', $domicilio, PDO::PARAM_STR);
    $stmt->bindParam(':logico', $logico, PDO::PARAM_INT);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();
    return $conexion->lastInsertId();
}

// Función para obtener los datos de un estudiante por su dni
function buscarEstudiante($dni)
{
    $conexion = obtenerConexionBD();

    $query = "SELECT * FROM estudiantes WHERE dni = :dni";
    $stmt = $conexion->prepare($query);
    $stmt->bindValue(':dni', $dni, PDO::PARAM_INT);
    $stmt->execute();

    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    return $estudiante;
}
