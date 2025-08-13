<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Estilos Principales y Bootstrap -->
<link rel="stylesheet" href="/PRY_CRUD/publics/bootstrap-5.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/PRY_CRUD/publics/css/estilos.css">
<link rel="stylesheet" href="/PRY_CRUD/publics/css/estilos_crudi.css">

<!-- Iconos de Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/PRY_CRUD/index.php"><i class="bi bi-person-badge"></i> Mi CRUD</a>
        <button class="navbar-toggler hamburger-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Elementos que solo se muestran si el usuario ha iniciado sesión -->
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/PRY_CRUD/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/PRY_CRUD/publics/php/listar.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/PRY_CRUD/publics/php/listar_notas.php">Notas</a>
                    </li>
                    <!-- Menú de Usuario y Cerrar Sesión -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill"></i> Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/PRY_CRUD/publics/php/logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                    <!-- Campana de Notificaciones -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="notiButton" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell-fill" style="font-size: 1.2rem;"></i>
                            <span id="notiBadge" class="position-absolute top-2 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                                0
                            </span>
                        </a>
                        <ul id="notiList" class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="notiButton" style="max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header text-center">Notificaciones</li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="nav-item ms-lg-3">
                    <select id="tema-seleccion" class="form-select form-select-sm" onchange="cambiarTema(this)">
                        <option value="tema-claro">Tema Claro</option>
                        <option value="tema-oscuro">Tema Oscuro</option>
                    </select>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toastContainer"></div>
</div>

<!-- Scripts de Notificaciones y Estilos -->
<script src="/PRY_CRUD/publics/js/notificaciones.js"></script>
<script src="/PRY_CRUD/publics/js/estilos.js"></script>