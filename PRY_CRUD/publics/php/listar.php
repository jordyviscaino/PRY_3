<?php
require_once 'proteger_pagina.php';
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$query = "SELECT * FROM usuarios ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
</head>
<body>
    
    <?php include 'header.php'; ?>

    <main class="container mt-5 mb-5">
        <div class="card cardtp shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-success"><i class="bi bi-people-fill"></i> Listado de Usuarios</h1>
                <div>
                    <a href="./crear.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Crear Usuario</a>
                    <a href="/PRY_CRUD/index.php" class="btn btn-outline-secondary"><i class="bi bi-box-arrow-left"></i> Salir al Menú</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($usuarios)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla_Usuarios" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Edad</th>
                                    <th style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo $usuario['id']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                                            <?php
                                                if (isset($_SESSION['ultimo_usuario_id']) && $usuario['id'] == $_SESSION['ultimo_usuario_id']) {
                                                    echo ' <span class="badge bg-primary">Nuevo</span>';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td><?php echo $usuario['edad']; ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm btn_editarU" data-id="<?php echo $usuario['id']; ?>">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm btn_eliminarU" data-usuario="<?php echo htmlspecialchars($usuario['nombre']); ?>" data-id="<?php echo $usuario['id']; ?>">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
          
                        if (isset($_SESSION['ultimo_usuario_id'])) {
                            unset($_SESSION['ultimo_usuario_id']);
                        }
                    ?>
                <?php else: ?>
                    <div class="alert alert-info mt-3" role="alert">
                        No hay usuarios registrados. ¡Crea el primero!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="editarUsuarioLabel"><i class="bi bi-pencil-square"></i> Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarUsuario">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_edad" class="form-label">Edad</label>
                            <input type="number" class="form-control" id="edit_edad" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="btnActualizar">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    
            const table = new DataTable('#tabla_Usuarios', {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json',
                }
            });

            const modalEditar = new bootstrap.Modal(document.getElementById("editarUsuarioModal"));
            let filaEditada = null; 
            <?php
            if (isset($_SESSION['notificacion'])) {
                $notificacion = $_SESSION['notificacion'];
                echo "agregarNotificacion('{$notificacion['texto']}', '{$notificacion['tipo']}');";
                unset($_SESSION['notificacion']); 
            }
            ?>

            document.getElementById('tabla_Usuarios').addEventListener('click', function(event) {
                const botonEditar = event.target.closest('.btn_editarU');
                const botonEliminar = event.target.closest('.btn_eliminarU');

                if (botonEditar) {
                    event.preventDefault();
                    filaEditada = botonEditar.closest('tr'); 
                    const idUsuario = botonEditar.dataset.id;
                    
                    fetch('./ObtenerPorid.php', {
                        method: 'POST',
                        body: JSON.stringify({ id: idUsuario }),
                        headers: { 'Content-Type': 'application/json' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            mostrarToast(data.error, 'danger');
                        } else {
                            document.getElementById("edit_id").value = data.id;
                            document.getElementById("edit_nombre").value = data.nombre;
                            document.getElementById("edit_email").value = data.email;
                            document.getElementById("edit_edad").value = data.edad;
                            modalEditar.show();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }

                if (botonEliminar) {
                    event.preventDefault();
                    const id = botonEliminar.dataset.id;
                    const usuario = botonEliminar.dataset.usuario;
                    eliminarUsuario(id, usuario, botonEliminar);
                }
            });

            document.getElementById('btnActualizar').addEventListener('click', function() {
                const id = document.getElementById("edit_id").value;
                const nombre = document.getElementById("edit_nombre").value;
                const email = document.getElementById("edit_email").value;
                const edad = document.getElementById("edit_edad").value;

                fetch('./actualizar.php', {
                    method: 'POST',
                    body: JSON.stringify({ id, nombre, email, edad }),
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalEditar.hide();

      
                        Swal.fire({
                            title: '¡Actualizado!',
                            text: 'El usuario ha sido actualizado correctamente.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        if (data.notificacion) {
                            agregarNotificacion(data.notificacion.texto, data.notificacion.tipo);
                        }

                        const escapedNombre = data.usuario.nombre.replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                        const botones = `
                            <button class="btn btn-primary btn-sm btn_editarU" data-id="${data.usuario.id}">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <button class="btn btn-danger btn-sm btn_eliminarU" data-usuario="${escapedNombre}" data-id="${data.usuario.id}">
                                <i class="bi bi-trash-fill"></i> Eliminar
                            </button>
                        `;

                        table.row(filaEditada).data([
                            data.usuario.id,
                            data.usuario.nombre,
                            data.usuario.email,
                            data.usuario.edad,
                            botones
                        ]).draw(false);
                    } else {
                        mostrarToast(data.error || 'No se pudo actualizar', 'danger');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            function eliminarUsuario(id, usuario, target) {
                Swal.fire({
                    title: '¿Estás seguro de eliminar al usuario?',
                    text: usuario,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('./eliminar.php', {
                            method: 'POST',
                            body: JSON.stringify({ id: id }),
                            headers: { 'Content-Type': 'application/json' }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
           
                                table.row(target.closest('tr')).remove().draw();
                                if(data.notificacion) {
                                    agregarNotificacion(data.notificacion.texto, data.notificacion.tipo);
                                }
                            } else {
                                mostrarToast(data.error || 'No se pudo eliminar', 'danger');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            }
        });
    </script>
</body>
</html>
