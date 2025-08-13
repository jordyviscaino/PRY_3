<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['usuario_id'])) {
    header('Location: /PRY_CRUD/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
</head>
<body>

    <?php include 'publics/php/header.php'; ?>

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 col-xl-6">
                <div class="card cardtp shadow-lg">
                    <div class="card-header">
                        <h1 class="h3 text-center text-primary mb-0"><i class="bi bi-box-arrow-in-right"></i> Inicio de Sesión</h1>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/668/668709.png" alt="Icono de login" style="width: 90px;">
                        </div>
                        <form id="loginForm" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="admin@correo.com" required>
                            </div>
                            <div class="form-text mb-3">Ingresa con cualquier correo electrónico registrado en el sistema.</div>
                            
                            <div id="loginError" class="alert alert-danger mt-3" style="display: none;" role="alert"></div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Ingresar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'publics/php/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginErrorDiv = document.getElementById('loginError');

            loginForm.addEventListener('submit', function(event) {
                event.preventDefault();
                loginErrorDiv.style.display = 'none'; // Ocultar errores previos

                const formData = new FormData(loginForm);

                fetch('publics/php/verificar_login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
               
                        window.location.href = '/PRY_CRUD/index.php';
                    } else {
               
                        loginErrorDiv.textContent = data.error || 'Error desconocido.';
                        loginErrorDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error de conexión:', error);
                    loginErrorDiv.textContent = 'Error de conexión con el servidor. Inténtelo de nuevo.';
                    loginErrorDiv.style.display = 'block';
                });
            });
        });
    </script>
</body>
</html>
