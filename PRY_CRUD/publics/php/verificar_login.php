<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

$email = $_POST['email'] ?? '';

if (empty($email)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'error' => 'El correo electrónico es obligatorio.']);
    exit;
}

try {
    $sql = "SELECT id, nombre, email FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si se encuentra un usuario con ese correo, se inicia la sesión.
    if ($usuario) {
        session_regenerate_id(true); // Previene la fijación de sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        
        echo json_encode(['success' => true]);
    } else {
        // Correo no encontrado
        echo json_encode(['success' => false, 'error' => 'El correo electrónico no se encuentra registrado.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos.']);
}
?>