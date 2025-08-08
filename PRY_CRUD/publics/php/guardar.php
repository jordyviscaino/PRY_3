<?php

require_once 'db.php';

if($_SERVER['REQUEST_METHOD'] === "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'] ?? '';

    $email = $_POST['email'] ?? '';

    $edad = $_POST['edad'] ?? '';
//preparar nuestra insercion sql
$sql = "INSERT INTO usuarios (nombre, email, edad) VALUES (:nombre, :email, :edad)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':edad', $edad);
    // Ejecutar la insercion

 try{
    $stmt->execute();
        // Redireccionar a la pagina de inicio
      header('Location: ../../index.html');
        exit;
    } catch (PDOException $e) {
        // Manejar errores de inserción
        echo "Error al insertar el usuario: " . $e->getMessage();
        exit;
    }
 
}


?>