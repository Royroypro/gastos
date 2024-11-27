<?php

header('Content-Type: application/json');

// Incluir el archivo de conexión a la base de datos
include 'app/config.php';
include 'sesion.php';

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $tipo = $_POST['tipo'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $categoria_gasto = $_POST['categoria_gasto'] ?? null;
    $categoria_entrada = $_POST['categoria_entrada'] ?? null;

    // Inicializar los valores de categorías según el tipo
    $id_categoria = ($tipo == 'Gasto') ? $categoria_gasto : null;
    $id_categoria_entrada = ($tipo == 'Ingreso') ? $categoria_entrada : null;

    try {
        // Consultar el saldo actual del usuario
        $stmt = $pdo->prepare("SELECT saldo_actual FROM transacciones WHERE id_usuario = :id_usuario ORDER BY id_transaccion DESC LIMIT 1");
        $stmt->execute([':id_usuario' => $id_usuario]);
        $saldo_anterior = $stmt->fetchColumn();

        // Insertar la transacción en la base de datos
        $sql = "INSERT INTO transacciones (id_usuario, id_categoria_gastos, id_categoria_entradas, tipo, monto, saldo_actual, descripcion, fecha_registro, imagenes)
                VALUES (:id_usuario, :id_categoria_gastos, :id_categoria_entradas, :tipo, :monto, :saldo_actual, :descripcion, :fecha_registro, :imagenes)";
        $stmt = $pdo->prepare($sql);
        $monto_saldo = ($tipo == 'Gasto') ? -$monto : $monto;
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_categoria_gastos' => $id_categoria,
            ':id_categoria_entradas' => $id_categoria_entrada,
            ':tipo' => $tipo,
            ':monto' => $monto,
            ':saldo_actual' => $saldo_anterior + $monto_saldo,
            ':descripcion' => $descripcion,
            ':fecha_registro' => $fecha,
            ':imagenes' => 'no-image.png'
        ]);

        // Obtener el último ID insertado
        $id_transaccion = $pdo->lastInsertId();

        // Procesar y guardar la imagen si se ha subido
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $directorio = 'imgs/';
            // Verificar si la carpeta 'imgs' existe y tiene permisos de escritura
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            if (is_writable($directorio)) {
                $nombre_imagen = $id_transaccion . '-' . $id_usuario . '_' . $tipo . '.jpg';
                $ruta_destino = $directorio . $nombre_imagen;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
                    // Actualizar la transacción con la ruta de la imagen
                    $updateSql = "UPDATE transacciones SET imagenes = :imagenes WHERE id_transaccion = :id_transaccion";
                    $pdo->prepare($updateSql)->execute([
                        ':imagenes' => $nombre_imagen,
                        ':id_transaccion' => $id_transaccion
                    ]);
                }
            }
        }

        // Mostrar el resultado en formato JSON
        echo json_encode([
            'ok' => true,
            'id_transaccion' => $id_transaccion
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'ok' => false,
            'error' => $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'ok' => false,
            'error' => $e->getMessage()
        ]);
    }
}
    
?>

