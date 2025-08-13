<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destruir todas las variables de sesión (como el ID y el nombre del usuario).
$_SESSION = array();

// Finalmente, destruir la sesión del servidor.
session_destroy();

// Redirigir al usuario a la página de inicio de sesión.
header("Location: /PRY_CRUD/login.php");
exit();
?>
