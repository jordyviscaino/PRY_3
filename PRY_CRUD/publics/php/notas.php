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
    <title>Ingresar Notas</title>
    <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card cardtp shadow-sm">
                    <div class="card-header">
                        <h1 class="h3 text-center text-success mb-0"><i class="bi bi-journal-plus"></i> Ingreso de Notas</h1>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <form id="notasForm">
                                    <div class="mb-3">
                                        <label for="usuario_id" class="form-label">Seleccionar Estudiante</label>
                                        <select class="form-select" style="color: black;"id="usuario_id" name="usuario_id" required>
                                            <option value="" disabled selected >-- Elija un estudiante --</option>
                                            <?php foreach ($usuarios as $usuario): ?>
                                                <option value="<?php echo htmlspecialchars($usuario['id']); ?>"><?php echo htmlspecialchars($usuario['nombre']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="materia_id" class="form-label">Seleccionar Materia</label>
                                        <select class="form-select"  style="color: black;" id="materia_id" name="materia_id" required>
                                            <option value="" disabled selected>-- Elija una materia --</option>
                                            <?php foreach ($materias as $materia): ?>
                                                <option value="<?php echo htmlspecialchars($materia['idM']); ?>"><?php echo htmlspecialchars($materia['nombre_m']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <h5 class="mb-3">Calificaciones</h5>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="n1" class="form-label">Nota 1</label>
                                            <input type="number" class="form-control text-black nota" id="n1" name="n1" placeholder="0.00" step="0.01" min="0" max="20" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="n2" class="form-label">Nota 2</label>
                                            <input type="number" class="form-control text-black  nota" id="n2" name="n2" placeholder="0.00" step="0.01" min="0" max="20" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="n3" class="form-label">Nota 3</label>
                                            <input type="number" class="form-control text-black  nota" id="n3" name="n3" placeholder="0.00" step="0.01" min="0" max="20" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="promedio" class="form-label">Promedio</label>
                                            <input type="number" class="form-control text-black " id="promedio" name="promedio" placeholder="0.00" readonly style="font-weight: bold;">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-center">
                                <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800" 
                                     alt="Imagen de notas académicas" 
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 300px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <button type="submit" form="notasForm" class="btn btn-success"><i class="bi bi-check-circle"></i> Guardar Notas</button>
                            <button type="reset" form="notasForm" class="btn btn-warning"><i class="bi bi-eraser"></i> Limpiar</button>
                        </div>
                        <a href="./listar_notas.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver a la lista</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Lógica para calcular promedio en tiempo real ---
            const notaInputs = document.querySelectorAll('.nota');
            const promedioInput = document.getElementById('promedio');

            function calcularPromedio() {
                let suma = 0;
                let count = 0;
                notaInputs.forEach(input => {
                    const valor = parseFloat(input.value);
                    if (!isNaN(valor)) {
                        suma += valor;
                        count++;
                    }
                });

                if (count > 0) {
                    // Calcula el promedio solo de las notas ingresadas
                    const promedio = suma / count;
                    promedioInput.value = promedio.toFixed(2);
                } else {
                    promedioInput.value = '';
                }
            }

            notaInputs.forEach(input => {
                input.addEventListener('input', calcularPromedio);
            });

            // --- Lógica para enviar el formulario ---
            const notasForm = document.getElementById("notasForm");
            notasForm.addEventListener("submit", function(event) {
                event.preventDefault();
                
                const formData = new FormData(notasForm);
                
                fetch('guardar_notas.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta del servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '¡Guardado!',
                            text: 'Las notas han sido registradas correctamente.',
                            icon: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });

                        if (data.notificacion) {
                            agregarNotificacion(data.notificacion.texto, data.notificacion.tipo);
                        }
                        
                        notasForm.reset(); // Limpiar el formulario
                        calcularPromedio(); // Resetear el campo de promedio
                    } else if (data.error) {
                        mostrarToast(data.error, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarToast('Ocurrió un error de conexión. Inténtalo de nuevo.', 'danger');
                });
            });
        });

        
    </script>
</body>

</html>