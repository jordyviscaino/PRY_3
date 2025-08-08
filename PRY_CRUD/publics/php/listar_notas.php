<?php
require_once 'db.php';

// Consulta para obtener las notas junto con el nombre del usuario y la materia
$query = "SELECT 
            notas.n1, 
            notas.n2, 
            notas.n3, 
            notas.promedio,
            usuarios.nombre AS nombre_usuario,
            materias.nombre_m AS nombre_materia
          FROM notas
          JOIN usuarios ON notas.usuario_id = usuarios.id
          JOIN materias ON notas.materia_id = materias.idM
          ORDER BY usuarios.nombre, materias.nombre_m";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de notas</title>
    <link rel="stylesheet" href="../bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos_crudi.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>
    <script src="../bootstrap-5.3.5-dist/js/bootstrap.bundle.js"></script>
</head>

<body>

    <!-- Impresion tabla con notas, promedio, nombre usuario y materia y mensaje de aprobado y reprobado-->

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card cardtp shadow-sm">
                    <div class="card-header">
                        <h1 class="h3 text-center text-success mb-0">Listado de notas</h1>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mt-3" id="tabla_Notas">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Materia</th>
                                        <th>Nota 1</th>
                                        <th>Nota 2</th>
                                        <th>Nota 3</th>
                                        <th>Promedio</th>
                                        <th>Estado</th>
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
                                            <tr>
                                                <td><?php echo htmlspecialchars($nota['nombre_usuario']); ?></td>
                                                <td><?php echo htmlspecialchars($nota['nombre_materia']); ?></td>
                                                <td><?php echo number_format($nota['n1'], 2); ?></td>
                                                <td><?php echo number_format($nota['n2'], 2); ?></td>
                                                <td><?php echo number_format($nota['n3'], 2); ?></td>
                                                <td><?php echo number_format($promedio, 2); ?></td>
                                                <td><span class="<?php echo $estadoClase; ?>"><?php echo $estado; ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="../../index.html" class="btn btn-outline-secondary me-2">Salir</a>
                        <a href="./notas.php" class="btn btn-success">Ingresar Nueva Nota</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#tabla_Notas');
    </script>
</body>

</html>