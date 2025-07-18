<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configuração do banco de dados Neon
$host = 'ep-blue-boat-a6n0xolm.us-east-2.aws.neon.tech';
$dbname = 'neondb';
$username = 'neondb_owner';
$password = 'XFBQQWIhHxIR';
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Inserir nova meta
        $input = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("
            INSERT INTO goals (title, objetivo, programa, indicadores, status, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([
            $input['title'] ?? '',
            $input['objetivo'] ?? '',
            $input['programa'] ?? '',
            $input['indicadores'] ?? '[]',
            $input['status'] ?? 'active'
        ]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Meta salva com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar meta']);
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Buscar todas as metas
        $stmt = $pdo->query("SELECT * FROM goals ORDER BY created_at DESC");
        $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $goals]);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
