<?php
require_once __DIR__ . '/../config.php';

header("Content-Type: application/json");

$endpoint = $_GET['endpoint'] ?? '';

switch ($endpoint) {
    case 'usuarios':
        $stmt = $pdo->query("SELECT * FROM usuarios");
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($dados);
        break;

    default:
        http_response_code(404);
        echo json_encode(['erro' => 'Endpoint não encontrado']);
        break;
}
?>
