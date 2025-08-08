<?php
require_once 'db.php';
$request = json_decode(file_get_contents('php://input'), true);

$id = $request["id"];


$query = "DELETE FROM usuarios WHERE id = :id";


$stmt = $pdo->prepare($query);

$stmt->bindParam(":id", $id);

try {
    $stmt->execute();
   
    if ($stmt->rowCount() > 0) {
         echo json_encode(["success" => "Usuario eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "Usuario no encontrado o no se pudo eliminar"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode (["error" => "Error en la base de datos: " . $e->getMessage()]);
}


?>