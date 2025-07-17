<?php
// Exemplo de função usando conexão global PDO
function buscarUsuarios() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
