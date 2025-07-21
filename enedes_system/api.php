<?php
require_once 'config.php';
require_once 'auth.php';

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($method === 'OPTIONS') {
    exit(0);
}

switch ($endpoint) {
    case 'dashboard-stats':
        if ($method === 'GET') getDashboardStats($pdo);
        break;
    case 'programs':
        if ($method === 'GET') getPrograms($pdo);
        break;
    case 'actions':
        if ($method === 'GET') getActions($pdo);
        break;
    case 'followups':
        if ($method === 'GET') getFollowups($pdo);
        break;
    case 'tasks':
        if ($method === 'GET') getTasks($pdo);
        break;
    case 'goals':
        if ($method === 'GET') {
            getGoals($pdo);
        } elseif ($method === 'POST') {
            insertGoal($pdo);
        }
        break;
    case 'cronograma':
        if ($method === 'GET') getCronograma($pdo);
        break;
    case 'inventario':
        if ($method === 'GET') getInventario($pdo);
        break;
    default:
        http_response_code(404);
        echo json_encode(["erro" => "Endpoint não encontrado"]);
        break;
}

// Funções

function getDashboardStats($pdo) {
    try {
        $stats = [];
        $tables = ['programs', 'actions', 'followups', 'tasks', 'goals', 'cronograma', 'inventario'];
        foreach ($tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats[$table] = $row['total'];
        }
        echo json_encode($stats);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao obter estatísticas']);
    }
}

function getPrograms($pdo) {
    fetchTable($pdo, 'programs');
}

function getActions($pdo) {
    fetchTable($pdo, 'actions');
}

function getFollowups($pdo) {
    fetchTable($pdo, 'followups');
}

function getTasks($pdo) {
    fetchTable($pdo, 'tasks');
}

function getGoals($pdo) {
    fetchTable($pdo, 'goals');
}

function insertGoal($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || empty($data['title'])) {
        http_response_
