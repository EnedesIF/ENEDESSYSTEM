<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // LISTAR TODAS AS AÇÕES
        $stmt = $pdo->query("SELECT * FROM acoes ORDER BY id ASC");
        $acoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($acoes);
        break;

    case 'POST':
        // INSERIR NOVA AÇÃO
        if (isset($input['meta_id'], $input['descricao'])) {
            $stmt = $pdo->prepare("INSERT INTO acoes (meta_id, descricao) VALUES (:meta_id, :descricao)");
            $stmt->execute([
                ':meta_id'   => $input['meta_id'],
                ':descricao' => $input['descricao']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Ação inserida com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'PUT':
        // ATUALIZAR AÇÃO
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'], $input['meta_id'], $input['descricao'])) {
            $stmt = $pdo->prepare("UPDATE acoes SET meta_id = :meta_id, descricao = :descricao WHERE id = :id");
            $stmt->execute([
                ':id'        => $params['id'],
                ':meta_id'   => $input['meta_id'],
                ':descricao' => $input['descricao']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Ação atualizada com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'DELETE':
        // DELETAR AÇÃO
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'])) {
            $stmt = $pdo->prepare("DELETE FROM acoes WHERE id = :id");
            $stmt->execute([':id' => $params['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Ação removida com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID não informado']);
        }
        break;
}
