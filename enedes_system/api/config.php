<?php
$host = 'ep-xxxxx.us-east-2.aws.neon.tech';
$db   = 'neondb';
$user = 'enedesifb';
$pass = 'Enedes2025*';
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
