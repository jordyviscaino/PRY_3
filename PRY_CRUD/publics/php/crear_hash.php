<?php



$passwordPlano = 'jordyviscaino123';


$hash = password_hash($passwordPlano, PASSWORD_DEFAULT);


echo "<h1>Generador de Hash de Contraseña</h1>";
echo "<p>Contraseña en texto plano: " . htmlspecialchars($passwordPlano) . "</p>";
echo "<p><strong>Copia este hash y pégalo en la columna 'password' de tu usuario en la base de datos:</strong></p>";
echo "<textarea rows='4' cols='80' readonly onclick='this.select();'>" . htmlspecialchars($hash) . "</textarea>";
?>