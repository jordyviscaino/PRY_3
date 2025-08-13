<?php
/**
 * Este script verifica si existe una sesión de usuario activa.
 * Si no existe, lo redirige a la página de inicio de sesión.
 * Debe ser incluido al principio de cualquier página que requiera autenticación.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) { // Si no existe la variable de sesión...
    header('Location: /PRY_CRUD/login.php'); // ...lo mandamos al login
    exit(); // Detenemos la ejecución del script.
}
?>