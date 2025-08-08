<?php
require_once 'db.php';


$query = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
//Verificar si hay usuarios

$queryMaterias = "SELECT * FROM materias";
$stmtMaterias = $pdo->prepare($queryMaterias);
$stmtMaterias->execute();
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar notas</title>
    <link rel="stylesheet" href="../bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos_crudi.css">
    <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
    <script src="../bootstrap-5.3.5-dist/js/bootstrap.bundle.js"></script>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class=" card cardtp">
                    <div class="card-header">
                        <h1 class="h3 text-center text-success mb-0">Ingreso de Notas</h1>
                    </div>
                    <div class="card-body">
                        <form id="notasForm">
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label text-black">Seleccionar Estudiante</label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="" disabled selected>-- Elija un estudiante --</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?php echo htmlspecialchars($usuario['id']); ?>"><?php echo htmlspecialchars($usuario['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="materia_id" class="form-label text-black">Seleccionar Materia</label>
                                <select class="form-select" id="materia_id" name="materia_id" required>
                                    <option value="" disabled selected>-- Elija una materia --</option>
                                    <?php foreach ($materias as $materia): ?>
                                        <option value="<?php echo htmlspecialchars($materia['idM']); ?>"><?php echo htmlspecialchars($materia['nombre_m']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <h5 class="text-black mb-3">Calificaciones</h5>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <label for="n1" class="form-label">Nota 1</label>
                                    <input type="number" class="form-control nota" id="n1" name="n1" placeholder="Nota 1" step="0.01" min="0" max="20" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="n2" class="form-label">Nota 2</label>
                                    <input type="number" class="form-control nota" id="n2" name="n2" placeholder="Nota 2" step="0.01" min="0" max="20" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="n3" class="form-label">Nota 3</label>
                                    <input type="number" class="form-control nota" id="n3" name="n3" placeholder="Nota 3" step="0.01" min="0" max="20" required>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <a href="../../index.html" class="btn btn-outline-secondary me-2">Salir</a>
                        <button type="submit" form="notasForm" class="btn btn-success">Guardar Notas</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const notasForm = document.getElementById("notasForm");
        notasForm.addEventListener("submit", function(event) {
            event.preventDefault();
            console.log("Formulario enviado");
            const formData = new FormData(notasForm);
            fetch('guardar_notas.php', {
                method: 'POST',
                body: formData
            }).then(function(response) {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            }).then(function(data) {
                console.log(data);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.success
                    }).then(() => {
                        notasForm.reset(); // Limpiar el formulario
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Ocurrió un error inesperado.'
                    });
                }
            }).catch(function(error) {
                console.error('Error:', error);

            });

        });
    </script>
</body>

</html>