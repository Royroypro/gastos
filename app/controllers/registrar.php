<?php

include_once '../config.php';
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$password = $_POST['password'];
$password2 = $_POST['password2'];

if ($password == $password2) {
    // Encriptar la contraseña
    $password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, correo, password) VALUES (:nombre, :apellido, :email, :password)");
        $stmt->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':password' => $password
        ));
        
        header('Location: ../../login.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "El correo ya está registrado.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "Las contraseñas no coinciden";
}

