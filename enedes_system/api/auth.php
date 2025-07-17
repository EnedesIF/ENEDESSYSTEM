<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once(__DIR__ . '/config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    exit;
}

// Ler dados JSON do corpo da requisição
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['usuario']) || !isset($data['senha'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Usuário e senha são obrigatórios']);
    exit;
}

$usuario = trim($data['usuario']);
$senha = trim($data['senha']);

try {
    // Verificar se a tabela de usuários existe
    $stmt = $pdo->prepare("
        SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_name = 'usuarios'
        )
    ");
    $stmt->execute();
    $tabelaExiste = $stmt->fetchColumn();

    if (!$tabelaExiste) {
        // Criar tabela de usuários se não existir
        $pdo->exec("
            CREATE TABLE usuarios (
                id SERIAL PRIMARY KEY,
                usuario VARCHAR(100) UNIQUE NOT NULL,
                senha VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Inserir usuário padrão (admin/admin123)
        $senhaHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        $stmt->execute(['admin', $senhaHash]);
        
        echo json_encode([
            'status' => 'info',
            'message' => 'Tabela criada! Use: admin/admin123'
        ]);
        exit;
    }

    // Buscar usuário
    $stmt = $pdo->prepare("SELECT id, senha FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $usuarioDb = $stmt->fetch();

    if ($usuarioDb && password_verify($senha, $usuarioDb['senha'])) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Login realizado com sucesso',
            'user_id' => $usuarioDb['id']
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuário ou senha inválidos'
        ]);
    }

} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro interno do servidor: ' . $e->getMessage()
    ]);
}
?>
