<?php

require_once 'db.php';
$request = json_decode(file_get_contents('php://input'), true);

$id = $request["id"];
$nombre = $request["nombre"];
$email = $request["email"];
$edad = $request["edad"];
//preparar nuestra insercion sql
$sql = "UPDATE usuarios SET nombre = :nombre, email = :email, edad = :edad WHERE usuarios.id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':edad', $edad);
    // Ejecutar la insercion

try {
   
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    if ($stmt->execute()) {
        // Devuelve un objeto con el éxito y los datos actualizados
        echo json_encode(['success' => 'Usuario actualizado correctamente', 'usuario' => $request]);
    
    } else {
        echo json_encode(["error" => "Usuario no encontrado"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
} 


?>