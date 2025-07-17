<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // LISTAR TODOS OS USUÁRIOS
        $stmt = $pdo->query("SELECT id, nome, email, perfil FROM usuarios ORDER BY id ASC");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($usuarios);
        break;

    case 'POST':
        // INSERIR NOVO USUÁRIO
        if (isset($input['nome'], $input['email'], $input['senha'], $input['perfil'])) {
            $hashedPassword = password_hash($input['senha'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)");
            $stmt->execute([
                ':nome'   => $input['nome'],
                ':email'  => $input['email'],
                ':senha'  => $hashedPassword,
                ':perfil' => $input['perfil']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Usuário cadastrado com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'PUT':
        // ATUALIZAR USUÁRIO
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'], $input['nome'], $input['email'], $input['perfil'])) {
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, perfil = :perfil WHERE id = :id");
            $stmt->execute([
                ':id'     => $params['id'],
                ':nome'   => $input['nome'],
                ':email'  => $input['email'],
                ':perfil' => $input['perfil']
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Usuário atualizado com sucesso']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;

    case 'DELETE':
        // REMOVER USUÁRIO
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'])) {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->execute([':id' => $params['id']]);
            echo json_encode(['status' => 'success', 'message' => 'Usuário excluído']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID não informado']);
        }
        break;
}
