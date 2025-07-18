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
        if ($method === 'GET') {
            getDashboardStats($pdo);
        }
        break;
    case 'programs':
        if ($method === 'GET') {
            getPrograms($pdo);
        }
        break;
    case 'actions':
        if ($method === 'GET') {
            getActions($pdo);
        }
        break;
    case 'followups':
        if ($method === 'GET') {
            getFollowups($pdo);
        }
        break;
    case 'tasks':
        if ($method === 'GET') {
            getTasks($pdo);
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(["erro" => "Endpoint não encontrado"]);
        break;
}

function getDashboardStats($pdo) {
    try {
        $stats = [];

        $tables = ['programs', 'actions', 'followups', 'tasks'];

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
    try {
        $stmt = $pdo->query("SELECT * FROM programs");
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($programs);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao obter programas']);
    }
}

function getActions($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM actions");
        $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($actions);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao obter ações']);
    }
}

function getFollowups($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM followups");
        $followups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($followups);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao obter follow-ups']);
    }
}

function getTasks($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM tasks");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao obter tarefas']);
    }
}
