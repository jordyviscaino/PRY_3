<?php

require_once 'db.php';
$request = json_decode(file_get_contents('php://input'), true);

$id = $request["id"];
$nombre = $request["nombre"];
$email = $request["email"];
$edad = $request["edad"];


$sql = "UPDATE usuarios SET nombre = :nombre, email = :email, edad = :edad WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':edad', $edad);

header('Content-Type: application/json');

try {
    if ($stmt->execute()) {
        $notificacion = [
            'texto' => 'Usuario "' . htmlspecialchars($nombre) . '" actualizado.',
            'tipo' => 'info'
        ];
      
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente', 'usuario' => $request, 'notificacion' => $notificacion]);
    } else {
        echo json_encode(["success" => false, "error" => "La ejecución de la consulta falló."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>