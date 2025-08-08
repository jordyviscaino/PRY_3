<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear usuarios</title>
  <link rel="stylesheet" href="../bootstrap-5.3.5-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/estilos_crudi.css">
</head>
<body>
  
  <div class="container3 text-black">
    <div class="row justify-content-center">
      <div class="col-md-6">

               <h1 class="text-center text-secondary">Crear un nuevo usuario</h1>


            <form method="POST" action="guardar.php">
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre">
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com">
                <div id="emailHelp" class="form-text">Nunca compartiremos tu correo con nadie más.</div>
              </div>

              <div class="mb-3">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" class="form-control" id="edad" name="edad" placeholder="Edad">
              </div>

              <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Crear usuario</button>
                <a href="../../index.html" class="btn btn-outline-danger">Salir</a>
              </div>
            </form>


      </div>
    </div>
  </div>

</body>
</html>
