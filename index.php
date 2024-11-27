
<?php
include_once 'app/config.php';
include_once 'sesion.php';



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Control de Gastos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header style="background-color: #6495ED" class="text-white text-center py-3">
        <h1>Control de Gastos</h1>
        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
        </form>
    </header>

    <main class="container my-4">
        <!-- Formulario para agregar transacciones -->
        <section class="form-section mb-5">
            <h2 class="text-center mb-4">Registrar Transacción</h2>
            <form id="transaction-form" action="procesar.php" method="POST" class="row g-3 needs-validation" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">

                <div class="col-md-6">
                    <label for="tipo" class="form-label">Tipo:</label>
                    <select name="tipo" id="tipo" class="form-select" required onchange="showCategory(this)">
                        <option value="" selected>--Seleccionar--</option>
                        <option value="Gasto">Gasto</option>
                        <option value="Ingreso">Ingreso</option>
                    </select>
                </div>

                <div id="categoria_gasto" class="col-md-6" style="display: none;">
                    <label for="categoria_gasto" class="form-label">Categoría Gastos:</label>
                    <select name="categoria_gasto" id="categoria_gasto" class="form-select" required>
                        <option value="" selected>--Seleccionar--</option>
                        <?php
                            $stmt = $pdo->prepare("SELECT * FROM categorias_gastos ORDER BY nombre");
                            $stmt->execute();
                            while($categoria = $stmt->fetch()){
                                echo "<option value='$categoria[id_categoria]'>$categoria[nombre]</option>";
                            }
                        ?>
                    </select>
                </div>

                <div id="categoria_entrada" class="col-md-6" style="display: none;">
                    <label for="categoria_entrada" class="form-label">Categoría Entradas:</label>
                    <select name="categoria_entrada" id="categoria_entrada" class="form-select" required>
                        <?php
                            $stmt = $pdo->prepare("SELECT * FROM categorias_entradas ORDER BY nombre");
                            $stmt->execute();
                            while($categoria = $stmt->fetch()){
                                echo "<option value='$categoria[id_categoria]'>$categoria[nombre]</option>";
                            }
                        ?>
                    </select>
                </div>

                <script>
                    function showCategory(select) {
                        if (select.value == 'Ingreso') {
                            document.getElementById('categoria_gasto').style.display = 'none';
                            document.getElementById('categoria_entrada').style.display = 'block';
                        } else {
                            document.getElementById('categoria_gasto').style.display = 'block';
                            document.getElementById('categoria_entrada').style.display = 'none';
                        }
                    }
                </script>

                <div class="col-md-6">
                    <label for="monto" class="form-label">Monto S/:</label>
                    <input type="number" name="monto" id="monto" step="0.01" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="fecha" class="form-label">Fecha y Hora:</label>
                    <input type="datetime-local" name="fecha" id="fecha" class="form-control" required value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>

                <div class="col-12">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control"></textarea>
                </div>
                
                <div class="col-12">
                    <label for="foto" class="form-label">Foto (Opcional):</label>
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <img id="preview" src="" alt="Vista previa de la imagen" style="max-width: 200px; margin-top: 10px;">
                </div>

                <script>
                    function previewImage(input) {
                        const preview = document.getElementById('preview');
                        const file = input.files[0];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    }
                </script>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary w-50 mt-3">Guardar</button>
                </div>
            </form>
        </section>

        <!-- Tabla para mostrar transacciones -->
        <section class="table-section">
            <h2 class="text-center mb-4">Historial de Transacciones</h2>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="buscar-tipo" class="form-label">Buscar por tipo:</label>
                    <select name="buscar-tipo" id="buscar-tipo" class="form-select" onchange="filtrarTransacciones(this)">
                        <option value="">Todos</option>
                        <option value="Gasto">Gasto</option>
                        <option value="Ingreso">Ingreso</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="buscar-categoria" class="form-label">Buscar por categor&iacute;a:</label>
                    <select name="buscar-categoria" id="buscar-categoria" class="form-select" onchange="filtrarTransacciones(this)">
                        <option value="">Todas</option>
                        <?php
                            $stmt = $pdo->prepare("SELECT * FROM categorias_gastos ORDER BY nombre");
                            $stmt->execute();
                            while($categoria = $stmt->fetch()){
                                echo "<option value='$categoria[id_categoria]'>$categoria[nombre]</option>";
                            }
                            $stmt = $pdo->prepare("SELECT * FROM categorias_entradas ORDER BY nombre");
                            $stmt->execute();
                            while($categoria = $stmt->fetch()){
                                echo "<option value='$categoria[id_categoria]'>$categoria[nombre]</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>N°</th>
                            <th>Tipo</th>
                            <th>Categor&iacute;a</th>
                            <th>Monto</th>
                            <th>Saldo Actual</th>
                            <th>Fecha</th>
                            <th>Descripci&oacute;n</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-transacciones">
                        <?php
                            $stmt = $pdo->prepare("SELECT t.id_transaccion, t.tipo, t.monto, t.saldo_actual, t.fecha_registro, t.descripcion, t.imagenes, cg.nombre AS categoria_gasto, ce.nombre AS categoria_entrada FROM transacciones t LEFT JOIN categorias_gastos cg ON t.id_categoria_gastos = cg.id_categoria LEFT JOIN categorias_entradas ce ON t.id_categoria_entradas = ce.id_categoria WHERE t.id_usuario = :id_usuario ORDER BY t.id_transaccion DESC");
                            $stmt->execute(array(
                                ':id_usuario' => $id_usuario
                            ));
                            $counter = 1;
                            while($transaccion = $stmt->fetch()){
                                $categoria = !empty($transaccion['categoria_gasto']) ? $transaccion['categoria_gasto'] : $transaccion['categoria_entrada'];
                                $imagen = !empty($transaccion['imagenes']) ? $transaccion['imagenes'] : 'no-image.png';
                                echo "<tr data-id-transaccion='{$transaccion['id_transaccion']}'>
                                    <td>" . ($stmt->rowCount() - $counter + 1) . "</td>
                                    <td>{$transaccion['tipo']}</td>
                                    <td>{$categoria}</td>
                                    <td>S/ {$transaccion['monto']}</td>
                                    <td>S/ {$transaccion['saldo_actual']}</td>
                                    <td>{$transaccion['fecha_registro']}</td>
                                    <td>{$transaccion['descripcion']}</td>
                                    <td style='display:none;'><img src='imgs/{$imagen}' alt='Imagen de la transacción' style='max-width: 200px;'></td>
                                    <td>
                                        <button class='btn btn-warning btn-sm' onclick='handleEdit(this)'>Mas</button>
                                        <button class='btn btn-danger btn-sm' onclick='handleDelete(this)'>Eliminar</button>
                                    </td>
                                </tr>";
                                $counter++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <script>
            function filtrarTransacciones(select) {
                const tipo = document.getElementById("buscar-tipo").value;
                const categoria = document.getElementById("buscar-categoria").value;
                const tbody = document.getElementById("tbody-transacciones");
                const rows = tbody.rows;

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const tipoRow = row.cells[1].innerText;
                    const categoriaRow = row.cells[2].innerText;

                    if ((tipo && tipoRow != tipo) || (categoria && categoriaRow != categoria)) {
                        row.style.display = 'none';
                    } else {
                        row.style.display = '';
                    }
                }
            }
        </script>

        <script>
            function handleEdit(button) {
                try {
                    const row = button.closest("tr");
                    const cells = row.cells;

                    const data = {
                        idTransaccion: row.dataset.idTransaccion,
                        tipo: cells[1]?.innerText || '',
                        categoria: cells[2]?.innerText || '',
                        monto: cells[3]?.innerText.replace("S/ ", "") || '0',
                        saldo_actual: cells[4]?.innerText.replace("S/ ", "") || '0',
                        fecha: cells[5]?.innerText || '',
                        descripcion: cells[6]?.innerText || '',
                        imagen: cells[7]?.querySelector("img")?.src.split("imgs/").pop() || 'no-image.png'
                    };

                    Object.keys(data).forEach(key => {
                        const input = document.getElementById(`modal${key.charAt(0).toUpperCase() + key.slice(1)}`);
                        if (input) {
                            if (input.tagName === 'IMG') {
                                input.src = `imgs/${data[key]}`;
                            } else {
                                input.value = data[key];
                            }
                        }
                    });

                    const editModal = new bootstrap.Modal(document.getElementById("editModal"));
                    editModal.show();

                } catch (error) {
                    console.error("Error en handleEdit:", error);
                }
            }

            function handleDelete(button) {
                const row = button.closest("tr");
                const idTransaccion = row.dataset.idTransaccion;
                if (confirm("¿Estás seguro de que deseas eliminar esta transacci&oacute;n?")) {
                    fetch(`app/controllers/eliminar.php?idTransaccion=${idTransaccion}`)
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            if (data.includes("eliminada correctamente")) {
                                row.remove();
                            }
                        })
                        .catch(error => console.error("Error al eliminar la transacci&oacute;n:", error));
                }
            }
        </script>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Transacción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" onsubmit="saveChanges(event)">
                    <input type="hidden" id="modalIdTransaccion">
                    <div class="mb-3">
                        <label for="modalTipo" class="form-label">Tipo</label>
                        <input type="text" class="form-control" id="modalTipo" disabled readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modalCategoria" class="form-label">Categoría</label>
                        <input type="text" class="form-control" id="modalCategoria" disabled readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modalMonto" class="form-label">Monto</label>
                        <input type="number" class="form-control" id="modalMonto" required readonly>
                    </div>
                   <!--  <div class="mb-3">
                        <label for="modalSaldoActual" class="form-label">Saldo Actual</label>
                        <input type="number" class="form-control" id="modalSaldoActual" required readonly>
                    </div> -->
                    <div class="mb-3">
                        <label for="modalFecha" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="modalFecha" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modalDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="modalDescripcion" rows="3" required readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <!-- <label for="modalImagen" class="form-label">Imagen</label> -->
                        <img id="modalImagen" src="" alt="Imagen de la transacción" style="max-width: 200px;">
                    </div>
                    <!-- <button type="submit" class="btn btn-primary">Guardar Cambios</button> -->
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function saveChanges(event) {
        event.preventDefault(); // Prevent form submission
        const form = event.target;

        const data = {
            id: document.getElementById("modalIdTransaccion").value,
            monto: document.getElementById("modalMonto").value,
            saldo_actual: document.getElementById("modalSaldoActual").value,
            fecha: document.getElementById("modalFecha").value,
            descripcion: document.getElementById("modalDescripcion").value
        };

        console.log("Datos a guardar:", data);
        // Aquí puedes realizar una llamada AJAX o manejar los datos según sea necesario
        // fetch(url, { method: 'PUT', body: JSON.stringify(data) })
        //     .then(response => response.text())
        //     .then(data => {
        //         alert(data);
        //         if (data.includes("actualizada correctamente")) {
        //             form.reset();
        //             const editModal = new bootstrap.Modal(document.getElementById("editModal"));
        //             editModal.hide();
        //         }
        //     })
        //     .catch(error => console.error("Error al actualizar la transacci&oacute;n:", error));
    }
</script>


    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2024 Sistema de Control de Gastos</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
