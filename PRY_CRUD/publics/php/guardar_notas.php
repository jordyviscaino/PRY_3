<?php

require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
 
    $usuario_id = $_POST['usuario_id'] ?? null;
    $materia_id = $_POST['materia_id'] ?? null;
    $n1 = $_POST['n1'] ?? null;
    $n2 = $_POST['n2'] ?? null;
    $n3 = $_POST['n3'] ?? null;

    if (empty($usuario_id) || empty($materia_id) || !isset($n1) || !isset($n2) || !isset($n3)) {
        die("Error: Todos los campos son requeridos.");
    }
    $promedio = ($n1 + $n2 + $n3) / 3;

    $query_usuario = "SELECT nombre FROM usuarios WHERE id = :usuario_id";
    $stmt_usuario = $pdo->prepare($query_usuario);
    $stmt_usuario->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt_usuario->execute();
    $usuario = $stmt_usuario->fetch();
    $nombreUsuario = $usuario ? $usuario['nombre'] : 'Desconocido';

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
        $ultimo_id = $pdo->lastInsertId();
        $_SESSION['ultimo_nota_id'] = $ultimo_id;
        $notificacion = [
            'texto' => 'Notas guardadas para ' . htmlspecialchars($nombreUsuario),
            'tipo' => 'success'
        ];
        echo json_encode(["success" => true, "message" => "Notas insertadas correctamente.", "notificacion" => $notificacion]);
    } else {
    
        echo json_encode(["error" => "Error al insertar las notas."]);
    }
}
?>