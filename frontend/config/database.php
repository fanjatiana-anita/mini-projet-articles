<?php
/**
 * Configuration de la connexion à la base de données PostgreSQL
 * Utilise PDO pour une connexion sécurisée
 */

// Paramètres de connexion (environnement local sans Docker)
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'iran_news');
define('DB_USER', 'postgres');
define('DB_PASS', 'olivia');  // Ton mot de passe PostgreSQL local

/**
 * Établit une connexion PDO à la base de données PostgreSQL
 * @return PDO Instance de connexion PDO
 */
function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            // Définir l'encodage UTF-8 pour PostgreSQL
            $pdo->exec("SET NAMES 'UTF8'");
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    return $pdo;
}
