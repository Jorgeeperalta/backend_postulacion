<?php

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbName = 'postulacionbd';
$username = 'root';
$password = 'root';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para obtener todas las carreras
function getCarreras()
{
    global $pdo;
    $query = "SELECT * FROM Carreras";
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener todas las materias de una carrera
function getMateriasPorCarrera($carreraId)
{
    global $pdo;
    $query = "SELECT * FROM Materias WHERE carrera_id = :carrera_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Función para obtener las notas de un estudiante en una materia
function getNotasPorMateria($estudianteId, $materiaId)
{
    global $pdo;
    $query = "SELECT * FROM Notas
              INNER JOIN Inscripciones ON Notas.inscripcion_id = Inscripciones.id
              WHERE Inscripciones.estudiante_id = :estudiante_id AND Inscripciones.materia_id = :materia_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materiaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ejemplo de uso

// Obtener todas las carreras
$carreras = getCarreras();
foreach ($carreras as $carrera) {
    echo "Carrera: " . $carrera['nombre'] . "<br>";
    
    // Obtener las materias de la carrera actual
    $materias = getMateriasPorCarrera($carrera['id']);
    foreach ($materias as $materia) {
        echo "- Materia: " . $materia['nombre'] . "<br>";
    }
    
    echo "<br>";
}

// Obtener los datos de un estudiante por su ID
$estudianteId = 1; // ID del estudiante a obtener
$estudiante = getEstudiante($estudianteId);
if ($estudiante) {
    echo "Estudiante: " . $estudiante['apellido_nombre'] . "<br>";
    echo "Carrera: " . $estudiante['carrera_id'] . "<br>";
    
    // Obtener las notas de un estudiante en una materia
    $materiaId = 1; // ID de la materia a consultar
    $notas = getNotasPorMateria($estudianteId, $materiaId);
    if ($notas) {
        echo "Notas:<br>";
        echo "Parcial 1: " . $notas['parcial_1'] . "<br>";
        echo "Parcial 2: " . $notas['parcial_2'] . "<br>";
        echo "Parcial 3: " . $notas['parcial_3'] . "<br>";
        echo "Parcial 4: " . $notas['parcial_4'] . "<br>";
        echo "Final: " . $notas['final'] . "<br>";
    } else {
        echo "No se encontraron notas para la materia especificada.";
    }
} else {
    echo "No se encontró el estudiante especificado.";
}

?>
