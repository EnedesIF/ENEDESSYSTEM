<?php
require_once __DIR__ . '/../config.php';

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$meta_id = $data['meta_id'] ?? null;
$etapa = $data['etapa'] ?? '';
$inicio = $data['inicio'] ?? '';
$prazo_final = $data['prazo_final'] ?? '';
$rubrica = $data['rubrica'] ?? '';
$valor_executado = $data['valor_executado'] ?? 0;

if (!$meta_id || !$etapa) {
    http_response_code(400);
    echo json_encode(['erro' => 'Campos obrigatórios não preenchidos']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO cronograma (meta_id, etapa, inicio, prazo_final, rubrica, valor_executado)
                       VALUES (:meta_id, :etapa, :inicio, :prazo_final, :rubrica, :valor_executado)");

try {
    $stmt->execute([
        'meta_id' => $meta_id,
        'etapa' => $etapa,
        'inicio' => $inicio,
        'prazo_final' => $prazo_final,
        'rubrica' => $rubrica,
        'valor_executado' => $valor_executado
    ]);
    echo json_encode(['status' => 'ok']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao inserir: ' . $e->getMessage()]);
}
?>
