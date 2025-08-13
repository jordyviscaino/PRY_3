<?php 
    require_once 'publics/php/proteger_pagina.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
</head>

<body>
    
    <?php 

        include './publics/php/header.php'; 
    ?>

    <main class="container mt-5 mb-5">
        <div class="card shadow-lg">
            <div class="card-header card-header-principal">
                <h1 class="card-title text-center mb-0 text-white">Panel de Administración</h1>
            </div>
            <div class="card-body text-center">
                <p class="lead">Bienvenido al panel de administración. Seleccione una de las siguientes opciones para comenzar.</p>
                <hr>
                <div class="row mt-4 gy-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h2 class="card-title"><i class="bi bi-people-fill text-primary"></i> Gestión de Usuarios</h2>
                                <p class="card-text flex-grow-1">Cree, visualice, actualice y elimine registros de usuarios.</p>
                                <div class="mt-auto">
                                    <a class="btn btn-primary" href="./publics/php/crear.php"><i class="bi bi-person-plus-fill"></i> Crear Usuario</a>
                                    <a class="btn btn-secondary" href="./publics/php/listar.php"><i class="bi bi-list-ul"></i> Listar Usuarios</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h2 class="card-title"><i class="bi bi-journal-text text-success"></i> Gestión de Notas</h2>
                                <p class="card-text flex-grow-1">Administre las notas de los estudiantes o registros académicos.</p>
                                <div class="mt-auto">
                                    <a class="btn btn-success" href="./publics/php/notas.php"><i class="bi bi-journal-plus"></i> Ingresar Notas</a>
                                    <a class="btn btn-warning" href="./publics/php/crear_materia.php"><i class="bi bi-plus-square"></i> Agregar Materia</a>
                                    <a class="btn btn-info text-white" href="./publics/php/listar_notas.php"><i class="bi bi-card-list"></i> Listar Notas</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer card-footer-principal text-center">
                <small>Para fines de desarrollo: <a href="./publics/php/db.php">Probar Conexión BD</a></small>
            </div>
        </div>
    </main>
    
    <?php include './publics/php/footer.php'; ?>

</body>
</html>