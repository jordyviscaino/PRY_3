<?php
require_once 'db.php';

// Leer el cuerpo de la solicitud JSON
$request = json_decode(file_get_contents('php://input'), true);

if (!isset($request['idN'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de nota no proporcionado.']);
    exit;
}

$idN = $request['idN'];

// Primero, obtenemos la información necesaria para la notificación ANTES de eliminar.
$query_info = "SELECT u.nombre AS nombre_usuario 
               FROM notas n
               JOIN usuarios u ON n.usuario_id = u.id
               WHERE n.idN = :idN";
$stmt_info = $pdo->prepare($query_info);
$stmt_info->bindParam(":idN", $idN, PDO::PARAM_INT);
$stmt_info->execute();
$nota_info = $stmt_info->fetch(PDO::FETCH_ASSOC);
$nombreUsuario = $nota_info ? $nota_info['nombre_usuario'] : 'Desconocido';

// Ahora, procedemos a eliminar la nota
$query_delete = "DELETE FROM notas WHERE idN = :idN";
$stmt_delete = $pdo->prepare($query_delete);
$stmt_delete->bindParam(":idN", $idN, PDO::PARAM_INT);

header('Content-Type: application/json');

try {
    $stmt_delete->execute();
   
    if ($stmt_delete->rowCount() > 0) {
        $notificacion = [
            'texto' => 'Registro de notas para "' . htmlspecialchars($nombreUsuario) . '" ha sido eliminado.',
            'tipo' => 'danger'
        ];
         echo json_encode([
            "success" => true, 
            "message" => "Nota eliminada correctamente",
            "notificacion" => $notificacion
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Nota no encontrada o no se pudo eliminar."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode (["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
