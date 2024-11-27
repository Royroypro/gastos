<?php

include_once 'app/config.php';

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;


}

$id_usuario = $_SESSION['usuario'];


// Additional logic for authenticated users can be added here

