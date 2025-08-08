<?php

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Recibir datos del formulario
    $usuario_id = $_POST['usuario_id'] ?? null;
    $materia_id = $_POST['materia_id'] ?? null;
    $n1 = $_POST['n1'] ?? null;
    $n2 = $_POST['n2'] ?? null;
    $n3 = $_POST['n3'] ?? null;

    if (empty($usuario_id) || empty($materia_id) || !isset($n1) || !isset($n2) || !isset($n3)) {
        die("Error: Todos los campos son requeridos.");
    }
    $promedio = ($n1 + $n2 + $n3) / 3;

    $sql = "INSERT INTO notas (usuario_id, materia_id, n1, n2, n3, promedio) VALUES (:usuario_id, :materia_id, :n1, :n2, :n3, :promedio)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
    $stmt->bindParam(':n1', $n1);
    $stmt->bindParam(':n2', $n2);
    $stmt->bindParam(':n3', $n3);
    $stmt->bindParam(':promedio', $promedio);

    header('Content-Type: application/json');
    if($stmt->execute()){
        echo json_encode(["success" => "Notas insertadas correctamente."]);
    } else {
        // Considera enviar un código de estado HTTP de error también
        // http_response_code(500);
        echo json_encode(["error" => "Error al insertar las notas."]);
    }
}
?>