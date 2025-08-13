<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Materia</title>
    <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card cardtp shadow-sm">
                    <div class="card-header">
                        <h1 class="h3 text-center text-warning mb-0"><i class="bi bi-plus-square"></i> Crear Nueva Materia</h1>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <form id="crearMateriaForm">
                                    <div class="mb-3">
                                        <label for="nombre_m" class="form-label">Nombre de la Materia</label>
                                        <input type="text" class="form-control" id="nombre_m" name="nombre_m" placeholder="Calculo Diferencial" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nrc" class="form-label">NRC (Número de Referencia de Curso)</label>
                                        <input type="text" class="form-control" id="nrc" name="nrc" placeholder="10234" required>
                                        <div class="form-text">El NRC es el identificador único del curso.</div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-center">
                                <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=800" 
                                     alt="Imagen de libros y materias" 
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 250px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <button type="submit" form="crearMateriaForm" class="btn btn-success"><i class="bi bi-check-circle"></i> Guardar Materia</button>
                            <button type="reset" form="crearMateriaForm" class="btn btn-warning"><i class="bi bi-eraser"></i> Limpiar</button>
                        </div>
                        <a href="/PRY_CRUD/index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver al Menú</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        
            const materiaForm = document.getElementById("crearMateriaForm");
            materiaForm.addEventListener("submit", function(event) {
                event.preventDefault();

                const formData = new FormData(materiaForm);

                fetch('guardarMateria.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ title: '¡Guardado!', text: 'La materia ha sido creada correctamente.', icon: 'success', timer: 2000, showConfirmButton: false });
                        if (data.notificacion) agregarNotificacion(data.notificacion.texto, data.notificacion.tipo);
                        materiaForm.reset();
                    } else {
                        mostrarToast(data.error || 'Ocurrió un error desconocido.', 'danger');
                    }
                })
                .catch(error => mostrarToast('Error de conexión con el servidor.', 'danger'));
            });

    </script>
</body>
</html>