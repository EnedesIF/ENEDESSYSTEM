<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // LISTAR TODOS OS COMENTÁRIOS
        $stmt = $pdo->query("SELECT * FROM comentarios ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
        break;

    case 'POST':
        // INSERIR NOVO COMENTÁRIO
        if (isset($input['acompanhamento_id'], $input['usuario'], $input['texto'])) {
            $stmt = $pdo->prepare("INSERT INTO comentarios (acompanhamento_id, usuario, texto) VALUES (:acompanhamento_id, :usuario, :texto)");
            $stmt->execute([
                ':acompanhamento_id' => $input['acompanhamento_id'],
                ':usuario'           => $input['usuario'],
                ':texto'             => $input['texto']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Come]()_
