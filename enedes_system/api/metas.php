<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // LISTAR TODAS AS METAS
        $stmt = $pdo->query("SELECT * FROM metas ORDER BY id ASC");
        $metas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($metas);
        break;

    case 'POST':
        // INSERIR NOVA META
        if (isset($input['programa_id'], $input['descricao'])) {
            $stmt = $pdo->prepare("INSERT INTO metas (programa_id, descricao) VALUES (:programa_id, :descricao)");
            $stmt->execute([
                ':programa_id' => $input['programa_id'],
                ':descricao'   => $input['descricao']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Meta inserida com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'PUT':
        // ATUALIZAR META
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'], $input['programa_id'], $input['descricao'])) {
            $stmt = $pdo->prepare("UPDATE metas SET programa_id = :programa_id, descricao = :descricao WHERE id = :id");
            $stmt->execute([
                ':id'          => $params['id'],
                ':programa_id' => $input['programa_id'],
                ':descricao'   => $input['descricao']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Meta atualizada com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'DELETE':
        // DELETAR META
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'])) {
            $stmt = $pdo->prepare("DELETE FROM metas WHERE id = :id");
            $stmt->execute([':id' => $params['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Meta removida com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID não informado']);
        }
        break;
}
