<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // LISTAR TODAS AS ENTRADAS DO CRONOGRAMA
        $stmt = $pdo->query("SELECT * FROM cronograma ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
        break;

    case 'POST':
        // INSERIR NOVA ENTRADA
        if (isset($input['meta_id'], $input['etapa'], $input['inicio'], $input['prazo_final'], $input['rubrica'], $input['valor_executado'])) {
            $stmt = $pdo->prepare("INSERT INTO cronograma (meta_id, etapa, inicio, prazo_final, rubrica, valor_executado) VALUES (:meta_id, :etapa, :inicio, :prazo_final, :rubrica, :valor_executado)");
            $stmt->execute([
                ':meta_id'         => $input['meta_id'],
                ':etapa'           => $input['etapa'],
                ':inicio'          => $input['inicio'],
                ':prazo_final'     => $input['prazo_final'],
                ':rubrica'         => $input['rubrica'],
                ':valor_executado' => $input['valor_executado']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Cronograma salvo com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'PUT':
        // ATUALIZAR ENTRADA
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'], $input['meta_id'], $input['etapa'], $input['inicio'], $input['prazo_final'], $input['rubrica'], $input['valor_executado'])) {
            $stmt = $pdo->prepare("UPDATE cronograma SET meta_id = :meta_id, etapa = :etapa, inicio = :inicio, prazo_final = :prazo_final, rubrica = :rubrica, valor_executado = :valor_executado WHERE id = :id");
            $stmt->execute([
                ':id'              => $params['id'],
                ':meta_id'         => $input['meta_id'],
                ':etapa'           => $input['etapa'],
                ':inicio'          => $input['inicio'],
                ':prazo_final'     => $input['prazo_final'],
                ':rubrica'         => $input['rubrica'],
                ':valor_executado' => $input['valor_executado']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Cronograma atualizado']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'DELETE':
        // DELETAR ENTRADA
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'])) {
            $stmt = $pdo->prepare("DELETE FROM cronograma WHERE id = :id");
            $stmt->execute([':id' => $params['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Entrada removida']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID não informado']);
        }
        break;
}
