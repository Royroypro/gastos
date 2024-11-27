<?php

include_once '../config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $correo = $_POST['usuario'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $stmt->execute(array(
            ':correo' => $correo
        ));
        $resultado = $stmt->fetch();

        if ($resultado && password_verify($password, $resultado['password'])) {
            session_start();
            $_SESSION['usuario'] = $resultado['id_usuario'];

            
            header('Location: ../../index.php');
            exit;
        } else {
            $mensaje = 'Usuario o contraseña incorrectos';
        }
    } catch (PDOException $e) {
        $mensaje = 'Error: ' . $e->getMessage();
    }
}
?>

