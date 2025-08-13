<?php

require_once 'db.php';

$query = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
//Verificar si hay usuarios
if (!$usuarios) {
    echo "No hay usuarios registrados";
    exit;
}

?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de usuarios</title>
    <link rel="stylesheet" href="../css/estilos_crudi.css">
    <link rel="stylesheet" href="../bootstrap-5.3.5-dist/css/bootstrap.min.css">

</head>

<body>

    <div class="container4 text-black">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h4 class="mb-0">Listado de usuarios</h4>

                <!-- Verificar si existen usuarios -->
                <?php
                if (!empty($usuarios)) {
                ?>
                    <table class="table table-striped mt-3" id="tabla_Usuarios">
                        <thead>
                            <tr>
                                <th class="bg-info">ID</th>
                                <th class="bg-info">Nombre</th>
                                <th class="bg-info">Email</th>
                                <th class="bg-info">Edad</th>
                                <th class="bg-info">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario) { ?>
                                <tr>
                                    <td><?php echo $usuario['id']; ?></td>
                                    <td><?php echo $usuario['nombre']; ?></td>
                                    <td><?php echo $usuario['email']; ?></td>
                                    <td><?php echo $usuario['edad']; ?></td>
                                    <td>
                                        <button id="" class="btn btn-primary btn_editarU"
                                            data-id="<?php echo $usuario['id']; ?>" data-bs-toggle="modal" data-bs-target="#editarUsuario">Editar</button>
                                        <button class="btn btn-danger">Eliminar</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>


                <?php }
                ?>

            </div>
            <div class="container">
                <a href="./crear.php" class="btn btn-success">Crear usuario</a>
                <a href="../../index.html" class="btn btn-outline-danger">Salir</a>
            </div>
        </div>


        <!-- modal para editar un usuario -->


        <div class="modal fade modal-lg" id="editarUsuario" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">


                    <div class="modal-header bg-warning text-white rounded-top-4">
                        <h5 class="modal-title text-black" id="exampleModalLabel ">
                            <i class="bi bi-info-circle-fill me-2 "></i> Editar Usario
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>


                    <div class="modal-body p-4">
                        <input type="hidden" id="id" value="">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" placeholder="ejemplo@correo.com">
                            <div id="emailHelp" class="form-text">Nunca compartiremos tu correo con nadie más.</div>
                        </div>

                        <div class="mb-3">
                            <label for="edad" class="form-label">Edad</label>
                            <input type="number" class="form-control" id="edad" placeholder="Edad">
                        </div>



                    </div>


                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="btnActualizar">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- modal para eliminar un usuario -->

        <script src="../bootstrap-5.3.5-dist/js/bootstrap.bundle.js"></script>

        <script>
            const modalE = new bootstrap.Modal(document.getElementById("editarUsuario"));


            var tablaU = document.getElementById("tabla_Usuarios");
            tablaU.addEventListener("click", function(event) {
                if (event.target && event.target.classList.contains("btn_editarU")) {
                    event.preventDefault();
                    console.log(event.target.dataset.id);
                    var idUsuario = event.target.dataset.id;
                    modalE.show();
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    fetch('./ObtenerPorid.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                id: idUsuario
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        }).then(function(response) {
                            //  console.log(response);
                            return response.json();

                        }).then(function(request) {
                            console.log(request.id);
                            document.getElementById("id").value = request.id;
                            document.getElementById("nombre").value = request.nombre;
                            document.getElementById("email").value = request.email;
                            document.getElementById("edad").value = request.edad;
                            console.log(request.nombre);
                            console.log(request.email);
                            console.log(request.edad);

                        })
                        .catch(function(error) {
                            console.log('Error:', error);
                        });
                }
            });
/* Ver usuario */
/* actualizar usuario */

            var actualizar = document.getElementById("btnActualizar");
            actualizar.addEventListener("click", function(event) {
                event.preventDefault();
                let id = document.getElementById("id").value;
                let nombre = document.getElementById("nombre").value;
                let email = document.getElementById("email").value;
                let edad = document.getElementById("edad").value;


                console.log(id, nombre, email, edad);
                fetch('./actualizar.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            id: id,
                            nombre: nombre,
                            email: email,
                            edad: edad
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(function(response) {
                        return response.json();
                    })
                    .then(function(request) {
                        console.log(request);
                        location.reload();
                    })
                    .catch(function(error) {
                        console.log('Error:', error);
                    }); 
            }); 
        </script>

</body>

</html>