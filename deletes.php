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

// Función para eliminar una carrera por su ID
function eliminarCarrera($carreraId)
{
    global $pdo;
    $query = "DELETE FROM Carreras WHERE id = :carrera_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':carrera_id', $carreraId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Función para eliminar una materia por su ID
function eliminarMateria($materiaId)
{
    global $pdo;
    $query = "DELETE FROM Materias WHERE id = :materia_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':materia_id', $materiaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Función para eliminar un estudiante por su ID
function eliminarEstudiante($estudianteId)
{
    global $pdo;
    $query = "DELETE FROM Estudiantes WHERE id = :estudiante_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':estudiante_id', $estudianteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

// Ejemplo de uso

// Eliminar una carrera
$carreraId = 1; // ID de la carrera a eliminar
$filasEliminadas = eliminarCarrera($carreraId);
echo "Filas eliminadas: " . $filasEliminadas . "<br>";

// Eliminar una materia
$materiaId = 1; // ID de la materia a eliminar
$filasEliminadas = eliminarMateria($materiaId);
echo "Filas eliminadas: " . $filasEliminadas . "<br>";

// Eliminar un estudiante
$estudianteId = 1; // ID del estudiante a eliminar
$filasEliminadas = eliminarEstudiante($estudianteId);
echo "Filas eliminadas: " . $filasEliminadas . "<br>";

?>
