<?php
// ============================================================
//  Connexion PostgreSQL — fichier unique pour tout le projet
//  Expose $pdo (backend Models) ET getConnection() (functions.php)
// ============================================================
$host     = 'localhost'; // Docker : 'db'
$port     = '5432';
$dbname   = 'iran_news';
$user     = 'postgres';
$password = 'fanjatiana';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

function getConnection(): PDO {
    global $pdo;
    return $pdo;
}