<?php
require_once('config.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $titulo = $data['titulo'] ?? '';
    $objetivo = $data['objetivo'] ?? '';
    $programa = $data['programa'] ?? '';
    $indicadores = json_encode($data['indicadores'] ?? []);
    $created_at = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO goals (titulo, objetivo, programa, indicadores, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titulo, $objetivo, $programa, $indicadores, $created_at]);

    echo json_encode(['success' => true, 'message' => 'Meta salva com sucesso.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
