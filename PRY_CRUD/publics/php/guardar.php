<?php

require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SERVER['REQUEST_METHOD'] === "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $edad = $_POST['edad'] ?? '';

    // Validación simple en el servidor
    if (empty($nombre) || empty($email) || empty($edad)) {
        header('Content-Type: application/json');
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Todos los campos son obligatorios."]);
        exit;
    }

    //preparar nuestra insercion sql
    $sql = "INSERT INTO usuarios (nombre, email, edad) VALUES (:nombre, :email, :edad)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':edad', $edad);

    header('Content-Type: application/json');
    try {
        $stmt->execute();
        $ultimo_id = $pdo->lastInsertId();
        $_SESSION['ultimo_usuario_id'] = $ultimo_id;
        $notificacion = ['texto' => 'Usuario "' . htmlspecialchars($nombre) . '" creado exitosamente.', 'tipo' => 'success'];
        echo json_encode(['success' => true, 'message' => 'Usuario creado.', 'notificacion' => $notificacion]);
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Error al crear el usuario: " . $e->getMessage()]);
    }
}
?>