<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {


    // Recibir datos del formulario
    $nombre_m = $_POST['nombre_m'] ?? '';
    $nrc = $_POST['nrc'] ?? '';

    // Validación en el servidor
    if (empty($nombre_m) || empty($nrc)) {
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Verificación de duplicados para evitar materias con el mismo nombre o NRC
    $check_sql = "SELECT idM FROM materias WHERE nombre_m = :nombre_m OR nrc = :nrc";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(':nombre_m', $nombre_m);
    $check_stmt->bindParam(':nrc', $nrc);
    $check_stmt->execute();

    if ($check_stmt->fetch()) {
        http_response_code(409); // Conflict
        echo json_encode(["success" => false, "error" => "Ya existe una materia con ese nombre o NRC."]);
        exit;
    }

    // Preparar la inserción SQL
    $sql = "INSERT INTO materias (nombre_m, nrc) VALUES (:nombre_m, :nrc)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre_m', $nombre_m);
    $stmt->bindParam(':nrc', $nrc);

    try {
        $stmt->execute();
        $notificacion = ['texto' => 'Materia "' . htmlspecialchars($nombre_m) . '" creada exitosamente.', 'tipo' => 'success'];
        echo json_encode(['success' => true, 'message' => 'Materia creada.', 'notificacion' => $notificacion]);
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["success" => false, "error" => "Error al crear la materia: " . $e->getMessage()]);
    }
}
?>