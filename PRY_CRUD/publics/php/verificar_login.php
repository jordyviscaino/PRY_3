<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

$email = $_POST['email'] ?? '';

if (empty($email)) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'error' => 'El correo electrónico es obligatorio.']);
    exit;
}

try {
    $sql = "SELECT id, nombre, email FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        session_regenerate_id(true); 
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        
        echo json_encode(['success' => true]);
    } else {

        echo json_encode(['success' => false, 'error' => 'El correo electrónico no se encuentra registrado.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos.']);
}
?>