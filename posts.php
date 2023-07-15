<?php

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbName = 'nombre_de_la_base_de_datos';
$username = 'nombre_de_usuario';
$password = 'contraseña';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para insertar una carrera
function insertarCarrera($nombre, $descripcion, $fechaApertura, $facultad, $aniosCursada)
{
    global $pdo;
    $query = "INSERT INTO Carreras (nombre, descripcion, fecha_apertura, facultad, anios_cursada)
              VALUES (:nombre, :descripcion, :fecha_apertura, :facultad, :anios_cursada)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_apertura', $fechaApertura, PDO::PARAM_STR);
    $stmt->bindParam(':facultad', $facultad, PDO::PARAM_STR);
    $stmt->bindParam(':anios_cursada', $aniosCursada, PDO::PARAM_INT);
    $stmt->execute();
    return $pdo->lastInsertId();
}

// Función para insertar una materia
function insertarMateria($nombre, $horasCursada, $formaAprobacion, $carreraId, $anioCarrera)
{
    global $pdo;
    $query = "INSERT INTO Materias (nombre, horas_cursada, forma_aprobacion, carrera_id, anio_carrera)
              VALUES (:nombre, :horas_cursada, :forma_aprobacion, :carrera_id, :anio_carrera)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':horas_cursada', $horasCursada, PDO::PARAM_INT);
    $stmt->bindParam(':forma_aprobacion', $formaAprobacion, PDO::PARAM_STR);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->bindParam(':anio_carrera', $anioCarrera, PDO::PARAM_INT);
    $stmt->execute();
    return $pdo->lastInsertId();
}

// Función para insertar un estudiante
function insertarEstudiante($dni, $apellidoNombre, $celular, $mail, $edad, $codigoPostal, $domicilio, $carreraId)
{
    global $pdo;
    $query = "INSERT INTO Estudiantes (dni, apellido_nombre, celular, mail, edad, codigo_postal, domicilio, carrera_id)
              VALUES (:dni, :apellido_nombre, :celular, :mail, :edad, :codigo_postal, :domicilio, :carrera_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
    $stmt->bindParam(':apellido_nombre', $apellidoNombre, PDO::PARAM_STR);
    $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':codigo_postal', $codigoPostal, PDO::PARAM_STR);
    $stmt->bindParam(':domicilio', $domicilio, PDO::PARAM_STR);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();
    return $pdo->lastInsertId();
}

// Ejemplo de uso

// Insertar una carrera
$carreraId = insertarCarrera("Ingeniería en Informática", "Carrera orientada hacia el desarrollo de proyectos software y hardware", "1995-04-01", "Ciencias exactas", 5);
echo "ID de la carrera insertada: " . $carreraId . "<br>";

// Insertar una materia
$materiaId = insertarMateria("Matemática discreta", 164, "Con examen final", $carreraId, 2);
echo "ID de la materia insertada: " . $materiaId . "<br>";

// Insertar un estudiante
$estudianteId = insertarEstudiante("12345678", "Juan Pérez", "123456789", "juan@example.com", 20, "1234", "Calle 123", $carreraId);
echo "ID del estudiante insertado: " . $estudianteId . "<br>";

?>
