<?php
require_once 'db.php';
require_once 'proteger_pagina.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Consulta para obtener todos los usuarios para los selects
$query_usuarios = "SELECT id, nombre FROM usuarios ORDER BY nombre";
$stmt_usuarios = $pdo->prepare($query_usuarios);
$stmt_usuarios->execute();
$usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener todas las materias para los selects
$query_materias = "SELECT idM, nombre_m FROM materias ORDER BY nombre_m";
$stmt_materias = $pdo->prepare($query_materias);
$stmt_materias->execute();
$materias = $stmt_materias->fetchAll(PDO::FETCH_ASSOC);

// Consulta principal para obtener las notas
$query = "SELECT
            notas.idN,
            notas.n1, 
            notas.n2, 
            notas.n3, 
            notas.promedio,
            usuarios.id AS usuario_id,
            usuarios.nombre AS nombre_usuario,
            materias.idM AS materia_id,
            materias.nombre_m AS nombre_materia
          FROM notas
          JOIN usuarios ON notas.usuario_id = usuarios.id
          JOIN materias ON notas.materia_id = materias.idM
          ORDER BY notas.idN DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En un entorno de producción, sería mejor registrar el error que mostrarlo.
    die("Error al obtener las notas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Notas</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <div class="card cardtp shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1 class="h3 text-success mb-0"><i class="bi bi-card-checklist"></i> Listado de Notas</h1>
                        <div>
                            <a href="./notas.php" class="btn btn-success"><i class="bi bi-journal-plus"></i> Ingresar Nota</a>
                            <button id="btnImprimirReporte" class="btn btn-danger"><i class="bi bi-file-earmark-pdf-fill"></i> Imprimir Reporte</button>
                            <a href="/PRY_CRUD/index.php" class="btn btn-outline-secondary"><i class="bi bi-box-arrow-left"></i> Salir al Menú</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mt-3" id="tabla_Notas" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Materia</th>
                                        <th class="text-center">Nota 1</th>
                                        <th class="text-center">Nota 2</th>
                                        <th class="text-center">Nota 3</th>
                                        <th class="text-center">Promedio</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($resultados)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No hay notas registradas.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($resultados as $nota): ?>
                                            <?php
                                                $promedio = (float) $nota['promedio'];
                                                $estado = $promedio >= 14 ? 'Aprobado' : 'Reprobado';
                                                $estadoClase = $promedio >= 14 ? 'badge bg-success' : 'badge bg-danger';
                                            ?>
                                            <tr data-id-nota="<?php echo $nota['idN']; ?>">
                                                <td data-col="usuario" data-id-usuario="<?php echo $nota['usuario_id']; ?>">
                                                    <?php echo htmlspecialchars($nota['nombre_usuario']); ?>
                                                    <?php
                                                        if (isset($_SESSION['ultimo_nota_id']) && $nota['idN'] == $_SESSION['ultimo_nota_id']) {
                                                            echo ' <span class="badge bg-primary">Nuevo</span>';
                                                        }
                                                    ?>
                                                </td>
                                                <td data-col="materia" data-id-materia="<?php echo $nota['materia_id']; ?>"><?php echo htmlspecialchars($nota['nombre_materia']); ?></td>
                                                <td class="text-center" data-col="n1"><?php echo number_format($nota['n1'], 2); ?></td>
                                                <td class="text-center" data-col="n2"><?php echo number_format($nota['n2'], 2); ?></td>
                                                <td class="text-center" data-col="n3"><?php echo number_format($nota['n3'], 2); ?></td>
                                                <td class="text-center fw-bold" data-col="promedio"><?php echo number_format($promedio, 2); ?></td>
                                                <td><span class="<?php echo $estadoClase; ?>"><?php echo $estado; ?></span></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm btn-editar-nota">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </button>
                                                    <button class="btn btn-danger btn-sm btn-eliminar-nota"
                                                            data-id-nota="<?php echo $nota['idN']; ?>"
                                                            data-info-nota="Notas de <?php echo htmlspecialchars($nota['nombre_usuario']); ?> en <?php echo htmlspecialchars($nota['nombre_materia']); ?>">
                                                        <i class="bi bi-trash-fill"></i> Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                            // Limpiar la variable de sesión después de usarla para que el badge no se muestre en recargas
                            if (isset($_SESSION['ultimo_nota_id'])) {
                                unset($_SESSION['ultimo_nota_id']);
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>

    <!-- Scripts para generar PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

    <!-- Scripts de DataTables y jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. INICIALIZACIÓN DE DATATABLES
            const table = new DataTable('#tabla_Notas', {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json',
                },
                order: [[0, 'asc']], // Ordenar por Estudiante por defecto
                columnDefs: [
                    { "orderable": false, "targets": 7 } // Deshabilitar orden en la columna de acciones
                ]
            });

            // 2. PREPARAR DATOS PARA LOS SELECTS
            const todosLosUsuarios = <?php echo json_encode($usuarios); ?>;
            const todasLasMaterias = <?php echo json_encode($materias); ?>;

            // 3. DELEGACIÓN DE EVENTOS PARA LA TABLA
            const tablaBody = document.getElementById('tabla_Notas').querySelector('tbody');
            let contenidoOriginalFila = {}; // Para guardar el estado original de la fila

            tablaBody.addEventListener('click', function(event) {
                const botonEditar = event.target.closest('.btn-editar-nota');
                const botonCancelar = event.target.closest('.btn-cancelar-edicion');
                const botonGuardar = event.target.closest('.btn-guardar-cambios');
                const botonEliminar = event.target.closest('.btn-eliminar-nota');

                if (botonEditar) {
                    const fila = botonEditar.closest('tr');
                    activarModoEdicion(fila);
                }

                if (botonCancelar) {
                    const fila = botonCancelar.closest('tr');
                    const idNota = fila.dataset.idNota;
                    cancelarModoEdicion(fila, contenidoOriginalFila[idNota]);
                }

                if (botonGuardar) {
                    const fila = botonGuardar.closest('tr');

                    // --- Validación simple en el cliente ---
                    let esValido = true;
                    fila.querySelectorAll('.nota-editable').forEach(input => {
                        input.classList.remove('is-invalid');
                        const valor = parseFloat(input.value);
                        if (input.value.trim() === '' || isNaN(valor) || valor < 0 || valor > 20) {
                            input.classList.add('is-invalid');
                            esValido = false;
                        }
                    });

                    if (!esValido) {
                        mostrarToast('Por favor, ingrese notas válidas (0-20).', 'danger');
                        return;
                    }

                    const datosActualizados = {
                        idN: fila.dataset.idNota,
                        usuario_id: fila.querySelector('select[name="usuario_id"]').value,
                        materia_id: fila.querySelector('select[name="materia_id"]').value,
                        n1: fila.querySelector('input[name="n1"]').value,
                        n2: fila.querySelector('input[name="n2"]').value,
                        n3: fila.querySelector('input[name="n3"]').value
                    };

                    fetch('actualizar_nota.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(datosActualizados)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Actualizado!',
                                text: 'Las notas se han actualizado correctamente.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            if (data.notificacion) {
                                agregarNotificacion(data.notificacion.texto, data.notificacion.tipo);
                            }

                            // Actualizar la fila en DataTables dinámicamente
                            const nota = data.nota;
                            const promedio = parseFloat(nota.promedio);
                            const estadoClase = promedio >= 14 ? 'badge bg-success' : 'badge bg-danger';
                            const estadoTexto = promedio >= 14 ? 'Aprobado' : 'Reprobado';
                            const botonesOriginales = contenidoOriginalFila[nota.idN].acciones;

                            table.row(fila).data([
                                nota.nombre_usuario,
                                nota.nombre_materia,
                                parseFloat(nota.n1).toFixed(2),
                                parseFloat(nota.n2).toFixed(2),
                                parseFloat(nota.n3).toFixed(2),
                                `<span class="fw-bold">${promedio.toFixed(2)}</span>`,
                                `<span class="${estadoClase}">${estadoTexto}</span>`,
                                botonesOriginales
                            ]).draw(false);

                            // Actualizar los data-attributes de la fila para futuras ediciones
                            const filaActualizada = table.row(fila).node();
                            filaActualizada.querySelector('td[data-col="usuario"]').dataset.idUsuario = nota.usuario_id;
                            filaActualizada.querySelector('td[data-col="materia"]').dataset.idMateria = nota.materia_id;

                        } else {
                            mostrarToast(data.error || 'No se pudo actualizar la nota.', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error en fetch:', error);
                        mostrarToast('Error de conexión al actualizar.', 'danger');
                    });
                }

                if (botonEliminar) {
                    const fila = botonEliminar.closest('tr');
                    const idNota = botonEliminar.dataset.idNota;
                    const infoNota = botonEliminar.dataset.infoNota;
                    eliminarNota(idNota, infoNota, botonEliminar);
                }
            });

            function activarModoEdicion(fila) {
                const idNota = fila.dataset.idNota;

                // Guardar el estado original si no lo hemos hecho ya
                if (!contenidoOriginalFila[idNota]) {
                    contenidoOriginalFila[idNota] = {
                        usuario: fila.cells[0].innerHTML,
                        materia: fila.cells[1].innerHTML,
                        n1: fila.cells[2].innerHTML,
                        n2: fila.cells[3].innerHTML,
                        n3: fila.cells[4].innerHTML,
                        promedio: fila.cells[5].innerHTML,
                        acciones: fila.cells[7].innerHTML
                    };
                }

                const tdUsuario = fila.cells[0];
                const tdMateria = fila.cells[1];
                const tdN1 = fila.cells[2];
                const tdN2 = fila.cells[3];
                const tdN3 = fila.cells[4];
                const tdPromedio = fila.cells[5];
                const tdAcciones = fila.cells[7];

                const idUsuarioActual = tdUsuario.dataset.idUsuario;
                const idMateriaActual = tdMateria.dataset.idMateria;
                const valN1 = parseFloat(tdN1.textContent);
                const valN2 = parseFloat(tdN2.textContent);
                const valN3 = parseFloat(tdN3.textContent);
                const valPromedio = parseFloat(tdPromedio.textContent);

                let selectUsuariosHTML = `<select name="usuario_id" class="form-select form-select-sm text-black">`;
                todosLosUsuarios.forEach(u => {
                    selectUsuariosHTML += `<option value="${u.id}" ${u.id == idUsuarioActual ? 'selected' : ''}>${u.nombre}</option>`;
                });
                tdUsuario.innerHTML = selectUsuariosHTML + `</select>`;

                let selectMateriasHTML = `<select name="materia_id" class="form-select form-select-sm text-black">`;
                todasLasMaterias.forEach(m => {
                    selectMateriasHTML += `<option value="${m.idM}" ${m.idM == idMateriaActual ? 'selected' : ''}>${m.nombre_m}</option>`;
                });
                tdMateria.innerHTML = selectMateriasHTML + `</select>`;

                // Convertir celdas de notas en inputs
                tdN1.innerHTML = `<input type="number" name="n1" class="form-control form-control-sm nota-editable text-black" value="${valN1.toFixed(2)}" min="0" max="20" step="0.01" required>`;
                tdN2.innerHTML = `<input type="number" name="n2" class="form-control form-control-sm nota-editable text-black" value="${valN2.toFixed(2)}" min="0" max="20" step="0.01" required>`;
                tdN3.innerHTML = `<input type="number" name="n3" class="form-control form-control-sm nota-editable text-black" value="${valN3.toFixed(2)}" min="0" max="20" step="0.01" required>`;
                tdPromedio.innerHTML = `<input type="number" name="promedio" class="form-control form-control-sm text-black" value="${valPromedio.toFixed(2)}" readonly style="font-weight: bold; background-color: #e9ecef;">`;

                tdAcciones.innerHTML = `
                    <button class="btn btn-success btn-sm btn-guardar-cambios"><i class="bi bi-check-circle"></i></button>
                    <button class="btn btn-secondary btn-sm btn-cancelar-edicion"><i class="bi bi-x-circle"></i></button>
                `;

                // Añadir listeners a los nuevos inputs de notas
                fila.querySelectorAll('.nota-editable').forEach(input => {
                    input.addEventListener('input', () => recalcularPromedioFila(fila));
                });
            }

            function cancelarModoEdicion(fila, original) {
                fila.cells[0].innerHTML = original.usuario;
                fila.cells[1].innerHTML = original.materia;
                fila.cells[2].innerHTML = original.n1;
                fila.cells[3].innerHTML = original.n2;
                fila.cells[4].innerHTML = original.n3;
                fila.cells[5].innerHTML = original.promedio;
                fila.cells[7].innerHTML = original.acciones;
            }

            function eliminarNota(id, info, target) {
                Swal.fire({
                    title: '¿Estás seguro de eliminar este registro?',
                    text: info,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('./eliminar_nota.php', {
                            method: 'POST',
                            body: JSON.stringify({ idN: id }),
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
                                mostrarToast(data.error || 'No se pudo eliminar la nota.', 'danger');
                            }
                        })
                        .catch(error => console.error('Error en fetch:', error));
                    }
                });
            }

            function recalcularPromedioFila(fila) {
                const inputs = fila.querySelectorAll('.nota-editable');
                const promedioInput = fila.querySelector('input[name="promedio"]');
                let suma = 0;
                let count = 0;

                inputs.forEach(input => {
                    const valor = parseFloat(input.value);
                    if (!isNaN(valor) && valor >= 0 && valor <= 20) {
                        suma += valor;
                    }
                    count++;
                });

                if (count === 3) {
                    const promedio = suma / 3;
                    promedioInput.value = promedio.toFixed(2);
                } else {
                    promedioInput.value = '0.00';
                }
            }

            // 4. LÓGICA PARA IMPRIMIR REPORTE PDF
            const btnImprimir = document.getElementById('btnImprimirReporte');
            btnImprimir.addEventListener('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Título del documento
                doc.setFontSize(18);
                doc.text('Reporte de Calificaciones', 14, 22);
                doc.setFontSize(11);
                doc.setTextColor(100);
                const fecha = new Date().toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
                doc.text(`Fecha de generación: ${fecha}`, 14, 30);

                // Preparar los datos para la tabla
                const head = [['Estudiante', 'Materia', 'Nota 1', 'Nota 2', 'Nota 3', 'Promedio', 'Estado']];
                const body = [];

                // Obtener todas las filas de DataTables, no solo las visibles
                table.rows().every(function() {
                    const rowData = this.data();
                    // Limpiar HTML de las celdas para el PDF
                    const estudiante = (rowData[0] || '').replace(/<span.*<\/span>/, '').trim();
                    const promedio = (rowData[5] || '').replace(/<[^>]*>?/gm, '');
                    const estado = (rowData[6] || '').replace(/<[^>]*>?/gm, '');

                    body.push([
                        estudiante, // Estudiante (limpiando el badge "Nuevo")
                        rowData[1], // Materia
                        rowData[2], // Nota 1
                        rowData[3], // Nota 2
                        rowData[4], // Nota 3
                        promedio,   // Promedio
                        estado      // Estado
                    ]);
                });

                doc.autoTable({ head, body, startY: 35, theme: 'striped', headStyles: { fillColor: [41, 128, 185] } });

                // Descargar el PDF
                doc.save('reporte_de_notas.pdf');
            });
        });
    </script>
</body>

</html>