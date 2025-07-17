<?php
require_once 'config.php';

// Define o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

// Lê os dados JSON enviados, se houver
$input = json_decode(file_get_contents('php://input'), true);

// Roteamento básico
switch ($method) {
    case 'GET':
        // LISTAR TODOS OS PROGRAMAS
        $stmt = $pdo->query("SELECT * FROM programas ORDER BY id ASC");
        $programas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($programas);
        break;

    case 'POST':
        // INSERIR NOVO PROGRAMA
        if (isset($input['nome'], $input['descricao'])) {
            $stmt = $pdo->prepare("INSERT INTO programas (nome, descricao) VALUES (:nome, :descricao)");
            $stmt->execute([
                ':nome' => $input['nome'],
                ':descricao' => $input['descricao']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Programa inserido com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'PUT':
        // ATUALIZAR UM PROGRAMA
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'], $input['nome'], $input['descricao'])) {
            $stmt = $pdo->prepare("UPDATE programas SET nome = :nome, descricao = :descricao WHERE id = :id");
            $stmt->execute([
                ':nome' => $input['nome'],
                ':descricao' => $input['descricao'],
                ':id' => $params['id']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Programa atualizado']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'DELETE':
        // DELETAR UM PROGRAMA
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'])) {
            $stmt = $pdo->prepare("DELETE FROM programas WHERE id = :id");
            $stmt->execute([':id' => $params['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Programa removido']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID não fornecido']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
        break;
}
