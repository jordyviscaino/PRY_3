<?php
require_once 'db.php';
$request = json_decode(file_get_contents('php://input'), true);

$id = $request["id"];


$query = "SELECT * FROM usuarios WHERE id = :id";


$stmt = $pdo->prepare($query);
$stmt->bindParam(":id", $id);

try {
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        header('Content-Type: application/json');
        echo json_encode($usuario);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Usuario no encontrado"]);
    }
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>