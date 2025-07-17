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
        echo json_encode(["error" => "Endpoint não encontrado"]);
}

function getDashboardStats($pdo) {
    try {
        $stats = [];
        
        // Contar programs
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM programs");
        $stats['programs'] = $stmt->fetch()['total'];
        
        // Contar actions
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM actions");
        $stats['actions'] = $stmt->fetch()['total'];
        
        // Contar followups
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM followups");
        $stats['followups'] = $stmt->fetch()['total'];
        
        // Contar tasks pendentes
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM tasks WHERE status = 'pending'");
        $stats['tasks_pending'] = $stmt->fetch()['total'];
        
        echo json_encode($stats);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function getPrograms($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM programs ORDER BY name");
        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($programs);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function getActions($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM actions ORDER BY created_at DESC");
        $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($actions);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function getFollowups($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM followups ORDER BY created_at DESC");
        $followups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($followups);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function getTasks($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
