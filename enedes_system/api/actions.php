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
            $stmt = $pdo->query("SELECT * FROM acoes ORDER BY id ASC");
            $acoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("🔍 Encontradas " . count($acoes) . " ações");
            echo json_encode($acoes);
        } catch (Exception $e) {
            error_log("❌ Erro no GET: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
        
    case 'POST':
        error_log("🔍 Executando POST - Inserir nova ação");
        error_log("🔍 Verificando campos obrigatórios...");
        error_log("🔍 meta_id existe? " . (isset($input['meta_id']) ? 'SIM' : 'NÃO'));
        error_log("🔍 descricao existe? " . (isset($input['descricao']) ? 'SIM' : 'NÃO'));
        
        if (isset($input['meta_id'], $input['descricao'])) {
            try {
                error_log("🔍 Preparando query INSERT...");
                $stmt = $pdo->prepare("INSERT INTO acoes (meta_id, descricao) VALUES (:meta_id, :descricao)");
                
                error_log("🔍 Executando INSERT com dados:");
                error_log("🔍 meta_id: " . $input['meta_id']);
                error_log("🔍 descricao: " . $input['descricao']);
                
                $result = $stmt->execute([
                    ':meta_id'   => $input['meta_id'],
                    ':descricao' => $input['descricao']
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
        
    // ... resto do código (PUT, DELETE)
}
?>
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
        error_log("🔍 meta_id existe? " . (isset($input['meta_id']) ? 'SIM' : 'NÃO'));
        error_log("🔍 descricao existe? " . (isset($input['descricao']) ? 'SIM' : 'NÃO'));
        
        if (isset($input['meta_id'], $input['descricao'])) {
            try {
                error_log("🔍 Preparando query INSERT...");
                // MUDANÇA: acoes -> actions
                $stmt = $pdo->prepare("INSERT INTO actions (meta_id, descricao) VALUES (:meta_id, :descricao)");
                
                error_log("🔍 Executando INSERT com dados:");
                error_log("🔍 meta_id: " . $input['meta_id']);
                error_log("🔍 descricao: " . $input['descricao']);
                
                $result = $stmt->execute([
                    ':meta_id'   => $input['meta_id'],
                    ':descricao' => $input['descricao']
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
        if (isset($params['id'], $input['meta_id'], $input['descricao'])) {
            try {
                // MUDANÇA: acoes -> actions
                $stmt = $pdo->prepare("UPDATE actions SET meta_id = :meta_id, descricao = :descricao WHERE id = :id");
                $stmt->execute([
                    ':id'        => $params['id'],
                    ':meta_id'   => $input['meta_id'],
                    ':descricao' => $input['descricao']
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
