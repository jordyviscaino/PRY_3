<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Usuario</title>
</head>
<body>

  <?php include 'header.php'; ?>
  
  <main class="container mt-5 mb-5">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-9">
        <div class="card cardtp shadow-sm">
          <div class="card-header">
            <h1 class="h3 text-center text-success mb-0">Crear Nuevo Usuario</h1>
          </div>
          <div class="card-body p-4">
            <div class="row align-items-center">
              <div class="col-lg-9">
                <form id="crearUsuarioForm">
                    <div class="mb-3">
                      <label for="nombre" class="form-label">Nombre Completo</label>
                      <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del usuario" required>
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Correo Electrónico</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                      <div class="form-text">Nunca compartiremos tu correo con nadie más.</div>
                    </div>
                    <div class="mb-3">
                      <label for="edad" class="form-label">Edad</label>
                      <input type="number" class="form-control" id="edad" name="edad" placeholder="Edad del usuario" required>
                    </div>
                </form>
              </div>
              <div class="col-lg-3 d-none d-lg-flex align-items-center justify-content-center">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800" 
                     alt="Imagen de registro de usuarios" 
                     class="img-fluid rounded shadow-sm"
                     style="max-width: 100%; height: auto;">
              </div>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <div>
              <button type="submit" form="crearUsuarioForm" class="btn btn-success"><i class="bi bi-check-circle"></i> Guardar</button>
              <button type="reset" form="crearUsuarioForm" class="btn btn-warning"><i class="bi bi-eraser"></i> Limpiar</button>
            </div>
            <a href="./listar.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script>
    const crearForm = document.getElementById("crearUsuarioForm");
    crearForm.addEventListener("submit", function(event) {
        event.preventDefault(); 
        const formData = new FormData(crearForm);

        fetch('guardar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.notificacion) {
                agregarNotificacion(data.notificacion.texto, data.notificacion.tipo);
                crearForm.reset(); 
            } else {
                mostrarToast(data.error || 'Ocurrió un error desconocido', 'danger');
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            mostrarToast('Error de conexión con el servidor.', 'danger');
        });
    });
  </script>
</body>
</html>
