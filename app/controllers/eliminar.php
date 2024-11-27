<?php

include_once '../config.php';
if (isset($_GET['idTransaccion'])) {
    $idTransaccion = $_GET['idTransaccion'];

    try {
        $stmt = $pdo->prepare("SELECT imagenes FROM transacciones WHERE id_transaccion = :idTransaccion");
        $stmt->execute(array(':idTransaccion' => $idTransaccion));
        $imagen = $stmt->fetchColumn();
        if ($imagen != 'no-image.png' && file_exists('../../imgs/')) {
            if (file_exists('../../imgs/' . $imagen)) {
                unlink('../../imgs/' . $imagen);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM transacciones WHERE id_transaccion = :idTransaccion");
        $stmt->execute(array(':idTransaccion' => $idTransaccion));
        echo "Transacción eliminada correctamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar la transacción: " . $e->getMessage();
    }
}

