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

// Función para actualizar una carrera por su ID
function actualizarCarrera($carreraId, $nombre, $descripcion, $fechaApertura, $facultad, $aniosCursada)
{
    global $pdo;
    $query = "UPDATE Carreras
              SET nombre = :nombre, descripcion = :descripcion, fecha_apertura = :fecha_apertura, facultad = :facultad, anios_cursada = :anios_cursada
              WHERE id = :carrera_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_apertura', $fechaApertura, PDO::PARAM_STR);
    $stmt->bindParam(':facultad', $facultad, PDO::PARAM_STR);
    $stmt->bindParam(':anios_cursada', $aniosCursada, PDO::PARAM_INT);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Función para actualizar una materia por su ID
function actualizarMateria($materiaId, $nombre, $horasCursada, $formaAprobacion, $carreraId, $anioCarrera)
{
    global $pdo;
    $query = "UPDATE Materias
              SET nombre = :nombre, horas_cursada = :horas_cursada, forma_aprobacion = :forma_aprobacion, carrera_id = :carrera_id, anio_carrera = :anio_carrera
              WHERE id = :materia_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':horas_cursada', $horasCursada, PDO::PARAM_INT);
    $stmt->bindParam(':forma_aprobacion', $formaAprobacion, PDO::PARAM_STR);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->bindParam(':anio_carrera', $anioCarrera, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materiaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Función para actualizar un estudiante por su ID
function actualizarEstudiante($estudianteId, $dni, $apellidoNombre, $celular, $mail, $edad, $codigoPostal, $domicilio, $carreraId)
{
    global $pdo;
    $query = "UPDATE Estudiantes
              SET dni = :dni, apellido_nombre = :apellido_nombre, celular = :celular, mail = :mail, edad = :edad, codigo_postal = :codigo_postal, domicilio = :domicilio, carrera_id = :carrera_id
              WHERE id = :estudiante_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
    $stmt->bindParam(':apellido_nombre', $apellidoNombre, PDO::PARAM_STR);
    $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':codigo_postal', $codigoPostal, PDO::PARAM_STR);
    $stmt->bindParam(':domicilio', $domicilio, PDO::PARAM_STR);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Ejemplo de uso

// Actualizar una carrera
$carreraId = 1; // ID de la carrera a actualizar
$filasActualizadas = actualizarCarrera($carreraId, "Ingeniería en Sistemas", "Carrera orientada al desarrollo de sistemas", "1995-04-01", "Ciencias Exactas", 5);
echo "Filas actualizadas: " . $filasActualizadas . "<br>";

// Actualizar una materia
$materiaId = 1; // ID de la materia a actualizar
$filasActualizadas = actualizarMateria($materiaId, "Álgebra Lineal", 120, "Con examen final", 1, 2);
echo "Filas actualizadas: " . $filasActualizadas . "<br>";

// Actualizar un estudiante
$estudianteId = 1; // ID del estudiante a actualizar
$filasActualizadas = actualizarEstudiante($estudianteId, "98765432", "María Rodríguez", "987654321", "maria@example.com", 22, "5678", "Calle 456", 1);
echo "Filas actualizadas: " . $filasActualizadas . "<br>";

?>
