<?php
require_once 'db.php';
$request = json_decode(file_get_contents('php://input'), true);

$id = $request["id"];


$pdo->beginTransaction();

try {
    $query_select = "SELECT nombre FROM usuarios WHERE id = :id";
    $stmt_select = $pdo->prepare($query_select);
    $stmt_select->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt_select->execute();
    $usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);
    $nombreUsuario = $usuario ? $usuario['nombre'] : 'Desconocido';

    $query_delete_notas = "DELETE FROM notas WHERE usuario_id = :id";
    $stmt_delete_notas = $pdo->prepare($query_delete_notas);
    $stmt_delete_notas->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt_delete_notas->execute();

 
    $query_delete_usuario = "DELETE FROM usuarios WHERE id = :id";
    $stmt_delete_usuario = $pdo->prepare($query_delete_usuario);
    $stmt_delete_usuario->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt_delete_usuario->execute();

 
    $pdo->commit();
   
    if ($stmt_delete_usuario->rowCount() > 0) {
        $notificacion = [
            'texto' => 'Usuario "' . htmlspecialchars($nombreUsuario) . '" y todas sus notas han sido eliminados.',
            'tipo' => 'danger'
        ];
         echo json_encode([
            "success" => true, 
            "message" => "Usuario eliminado correctamente",
            "notificacion" => $notificacion
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Usuario no encontrado o no se pudo eliminar."]);
    }
} catch (PDOException $e) {

    $pdo->rollBack();
    http_response_code(500);
    echo json_encode (["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>