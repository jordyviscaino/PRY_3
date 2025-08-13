<?php
require_once 'db.php';


$request = json_decode(file_get_contents('php://input'), true);


if (!isset($request['idN'], $request['usuario_id'], $request['materia_id'], $request['n1'], $request['n2'], $request['n3'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    exit;
}

$idN = $request['idN'];
$usuario_id = $request['usuario_id'];
$materia_id = $request['materia_id'];
$n1 = $request['n1'];
$n2 = $request['n2'];
$n3 = $request['n3'];


$promedio = ($n1 + $n2 + $n3) / 3;

// Preparar la consulta SQL
$sql = "UPDATE notas 
        SET usuario_id = :usuario_id, 
            materia_id = :materia_id, 
            n1 = :n1, 
            n2 = :n2, 
            n3 = :n3, 
            promedio = :promedio 
        WHERE idN = :idN";

$stmt = $pdo->prepare($sql);


$stmt->bindParam(':idN', $idN, PDO::PARAM_INT);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
$stmt->bindParam(':n1', $n1);
$stmt->bindParam(':n2', $n2);
$stmt->bindParam(':n3', $n3);
$stmt->bindParam(':promedio', $promedio);

header('Content-Type: application/json');

try {
    if ($stmt->execute()) {
    
        $query_nombres = "SELECT u.nombre, m.nombre_m 
                          FROM usuarios u, materias m 
                          WHERE u.id = :uid AND m.idM = :mid";
        $stmt_nombres = $pdo->prepare($query_nombres);
        $stmt_nombres->bindParam(':uid', $usuario_id);
        $stmt_nombres->bindParam(':mid', $materia_id);
        $stmt_nombres->execute();
        $nombres = $stmt_nombres->fetch(PDO::FETCH_ASSOC);

        $notificacion = [
            'texto' => 'Notas para "' . htmlspecialchars($nombres['nombre']) . '" actualizadas.',
            'tipo' => 'info'
        ];


        $responseData = [
            'idN' => $idN,
            'usuario_id' => $usuario_id,
            'nombre_usuario' => $nombres['nombre'],
            'materia_id' => $materia_id,
            'nombre_materia' => $nombres['nombre_m'],
            'n1' => $n1,
            'n2' => $n2,
            'n3' => $n3,
            'promedio' => $promedio
        ];

        echo json_encode([
            'success' => true, 
            'message' => 'Nota actualizada correctamente', 
            'nota' => $responseData, 
            'notificacion' => $notificacion
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "La ejecución de la consulta falló."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
