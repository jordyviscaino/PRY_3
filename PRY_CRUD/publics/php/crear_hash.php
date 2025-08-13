<?php
// Este es un script de un solo uso para generar una contraseña segura.

// 1. Define la contraseña que quieres usar para iniciar sesión.
$passwordPlano = 'jordyviscaino123';

// 2. PHP la convierte a un formato seguro (hash).
$hash = password_hash($passwordPlano, PASSWORD_DEFAULT);

// 3. Muestra el resultado para que lo copies.
echo "<h1>Generador de Hash de Contraseña</h1>";
echo "<p>Contraseña en texto plano: " . htmlspecialchars($passwordPlano) . "</p>";
echo "<p><strong>Copia este hash y pégalo en la columna 'password' de tu usuario en la base de datos:</strong></p>";
echo "<textarea rows='4' cols='80' readonly onclick='this.select();'>" . htmlspecialchars($hash) . "</textarea>";
?>