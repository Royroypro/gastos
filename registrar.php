<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Control de Gastos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 600px;">
            <h1 class="text-center mb-4">Sistema de Control de Gastos</h1>
            <h3 class="text-center mb-4">Crear Cuenta</h3>
            <form action="app/controllers/registrar.php" method="POST">
                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
                </div>
                <!-- Apellido -->
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingresa tu apellido" required>
                </div>
                <!-- Correo Electrónico -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
                </div>
                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Crea una contraseña" required>
                </div>
                <!-- Confirmar Contraseña -->
                <div class="mb-3">
                    <label for="password2" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="Repite la contraseña" required>
                </div>
                <!-- Botón de Registro -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Registrarse</button>
                </div>
                <!-- Enlace de inicio de sesión -->
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none">¿Ya tienes una cuenta? Inicia Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

