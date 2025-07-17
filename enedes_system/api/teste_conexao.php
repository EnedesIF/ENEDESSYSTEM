<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT NOW()");
    $row = $stmt->fetch();
    echo "✅ Conexão bem-sucedida com o banco Neon!<br>";
    echo "🕒 Data/hora atual no servidor: " . $row[0];
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}
?>
