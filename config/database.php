<?php
/**
 * Connexion PDO — fonctionne en local ET en Docker
 *
 * En local (XAMPP/LAMPP) : utilise les valeurs par défaut ci-dessous
 * En Docker              : les variables d'environnement du conteneur
 *                          (définies dans docker-compose.yml) priment
 */

$host     = getenv('DB_HOST')     ?: 'localhost';
$port     = getenv('DB_PORT')     ?: '5432';
$dbname   = getenv('DB_NAME')     ?: 'iran_news';
$user     = getenv('DB_USER')     ?: 'postgres';
$password = getenv('DB_PASSWORD') ?: 'fanjatiana'; // votre mot de passe local

function getConnection(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    global $host, $port, $dbname, $user, $password;

    try {
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        http_response_code(500);
        die('<h1>Erreur de connexion à la base de données</h1><p>' . $e->getMessage() . '</p>');
    }

    return $pdo;
}
