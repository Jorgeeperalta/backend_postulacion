<?php

function obtenerConexionBD()
{
    $configuracionBD = obtenerConfiguracionBD();

    $host = $configuracionBD['host'];
    $dbName = $configuracionBD['dbName'];
    $username = $configuracionBD['username'];
    $password = $configuracionBD['password'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

function obtenerConfiguracionBD()
{
    // $configuracion = [
    //     'host' => 'localhost',
    //     'dbName' => 'postulacionbd',
    //     'username' => 'root',
    //     'password' => 'root'
    // ];
    $configuracion = [
        'host' => 'MYSQL.site4now.net',
        'dbName' => 'db_a47d48',
        'username' => 'a47d48',
        'password' => ''
    ];

    return $configuracion;
}
