<?php
require_once(__DIR__ . '/config.php');

$data = json_decode(file_get_contents("php://input"), true);
$usuario = $data['usuario'] ?? '';
$senha = $data['senha'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND senha = :senha");
$stmt->execute(['usuario' => $usuario, 'senha' => $senha]);
$user = $stmt->fetch();

if ($user) {
    echo json_encode(['status' => 'success', 'user' => $user]);
} else {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Login inválido']);
}
?>
