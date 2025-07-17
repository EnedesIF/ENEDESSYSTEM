<?php
$host = 'ep-sua-instancia.neon.tech';
$db   = 'enedes';
$user = 'usuario';
$pass = 'senha';
$port = '5432';
$sslmode = 'require';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslmode";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("❌ Erro ao conectar ao banco: " . $e->getMessage());
}
?>
