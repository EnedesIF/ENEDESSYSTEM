<?php
require_once(__DIR__ . '/api/config.php');

try {
    $stmt = $pdo->query("SELECT NOW()");
    $row = $stmt->fetch();
    echo "✅ Conexão com o banco bem-sucedida.<br>";
    echo "🕒 Data/hora do servidor PostgreSQL: " . $row[0];
} catch (PDOException $e) {
    echo "❌ Erro ao conectar ao banco: " . $e->getMessage();
}
?>
