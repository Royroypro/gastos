<?php

// Variables de conexion a la base de datos
define('DB_HOST', 'sehuacho.com');
define('DB_USER', 'root');
define('DB_PASS', '*Royner123123*');
define('DB_NAME', 'gastos');

// Crear la conexion utilizando PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>