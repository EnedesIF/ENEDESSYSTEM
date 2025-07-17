<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // LISTAR TODOS OS ACOMPANHAMENTOS
        $stmt = $pdo->query("SELECT * FROM acompanhamentos ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
        break;

    case 'POST':
        // INSERIR NOVO ACOMPANHAMENTO
        if (isset($input['acao_id'], $input['descricao'], $input['data'])) {
            $stmt = $pdo->prepare("INSERT INTO acompanhamentos (acao_id, descricao, data) VALUES (:acao_id, :descricao, :data)");
            $stmt->execute([
                ':acao_id'   => $input['acao_id'],
                ':descricao' => $input['descricao'],
                ':data'      => $input['data']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Acompanhamento cadastrado com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
