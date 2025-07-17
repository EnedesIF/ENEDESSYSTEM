<?php
require_once 'config.php';

// 🔍 LOGS PARA DEBUG
error_log("🔍 === ACTIONS.PHP CHAMADO ===");
error_log("🔍 Método: " . $_SERVER['REQUEST_METHOD']);
error_log("🔍 Headers: " . print_r(getallheaders(), true));

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

error_log("🔍 Input recebido: " . print_r($input, true));
error_log("🔍 Raw input: " . file_get_contents('php://input'));

switch ($method) {
    case 'GET':
        error_log("🔍 Executando GET - Listar ações");
        try {
            // MUDANÇA: acoes -> actions
            $stmt = $pdo->query("SELECT * FROM actions ORDER BY id ASC");
            $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("🔍 Encontradas " . count($actions) . " ações");
            echo json_encode($actions);
        } catch (Exception $e) {
            error_log("❌ Erro no GET: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
        
    case 'POST':
        error_log("🔍 Executando POST - Inserir nova ação");
        error_log("🔍 Verificando campos obrigatórios...");
        error_log("🔍 title existe? " . (isset($input['title']) ? 'SIM' : 'NÃO'));
        error_log("🔍 description existe? " . (isset($input['description']) ? 'SIM' : 'NÃO'));
        
        if (isset($input['title'], $input['description'])) {
            try {
                error_log("🔍 Preparando query INSERT...");
                // USANDO OS CAMPOS CORRETOS DA TABELA
                $stmt = $pdo->prepare("INSERT INTO actions (title, programa, description, responsavel, status) VALUES (:title, :programa, :description, :responsavel, :status)");
                
                error_log("🔍 Executando INSERT com dados:");
                error_log("🔍 title: " . $input['title']);
                error_log("🔍 description: " . $input['description']);
                
                $result = $stmt->execute([
                    ':title'       => $input['title'],
                    ':programa'    => $input['programa'] ?? null,
                    ':description' => $input['description'],
                    ':responsavel' => $input['responsavel'] ?? null,
                    ':status'      => $input['status'] ?? 'pending'
                ]);
                
                if ($result) {
                    $lastId = $pdo->lastInsertId();
                    error_log("✅ INSERT executado com sucesso! ID: " . $lastId);
                    echo json_encode(['status' => 'success', 'message' => 'Ação inserida com sucesso', 'id' => $lastId]);
                } else {
                    error_log("❌ INSERT falhou - sem erro específico");
                    $errorInfo = $stmt->errorInfo();
                    error_log("❌ Error Info: " . print_r($errorInfo, true));
                    echo json_encode(['status' => 'error', 'message' => 'Falha ao inserir']);
                }
                
            } catch (Exception $e) {
                error_log("❌ Erro no POST: " . $e->getMessage());
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            error_log("❌ Dados incompletos recebidos");
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;
        
    case 'PUT':
        // ATUALIZAR AÇÃO
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'], $input['title'], $input['description'])) {
            try {
                // MUDANÇA: acoes -> actions
                $stmt = $pdo->prepare("UPDATE actions SET title = :title, programa = :programa, description = :description, responsavel = :responsavel, status = :status WHERE id = :id");
                $stmt->execute([
                    ':id'          => $params['id'],
                    ':title'       => $input['title'],
                    ':programa'    => $input['programa'] ?? null,
                    ':description' => $input['description'],
                    ':responsavel' => $input['responsavel'] ?? null,
                    ':status'      => $input['status'] ?? 'pending'
                ]);
                echo json_encode(['status' => 'success', 'message' => 'Ação atualizada com sucesso']);
            } catch (Exception $e) {
                error_log("❌ Erro no PUT: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        }
        break;
        
    case 'DELETE':
        // DELETAR AÇÃO
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (isset($params['id'])) {
            try {
                // MUDANÇA: acoes -> actions
                $stmt = $pdo->prepare("DELETE FROM actions WHERE id = :id");
                $stmt->execute([':id' => $params['id']]);
                echo json_encode(['status' => 'success', 'message' => 'Ação removida com sucesso']);
            } catch (Exception $e) {
                error_log("❌ Erro no DELETE: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID não informado']);
        }
        break;
}
?>
