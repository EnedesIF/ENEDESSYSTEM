<?php
$host = 'ep-sweet-bar-aec2ww8z-pooler.c-2.us-east-2.aws.neon.tech';
$db   = 'neondb';
$user = 'neondb_owner';  // ← ASPA CORRIGIDA
$pass = 'npg_igfwntzh0j1C';
$port = "5432";
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db;sslmode=require", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
