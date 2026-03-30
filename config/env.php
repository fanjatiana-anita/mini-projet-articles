<?php
/**
 * Gestionnaire de variables d'environnement (.env)
 * Charge les conf sensibles stockées dans .env
 */

function loadEnv(string $path = ''): void {
    if ($path === '') {
        $path = __DIR__ . '/../.env';
    }
    
    if (!file_exists($path)) {
        // Si .env n'existe pas, utiliser .env.example comme fallback
        $path = __DIR__ . '/../.env.example';
    }
    
    if (!file_exists($path)) {
        return; // Silencieux si aucun fichier
    }
    
    // Lire fichier ligne par ligne
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignorer les commentaires
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parser KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Retirer quotes si présentes
            if (preg_match('/^["\'](.+)["\']$/', $value, $m)) {
                $value = $m[1];
            }
            
            // Définir comme constant si pas déjà défini
            if (!defined($key) && !empty($key)) {
                define($key, $value);
            }
            
            // Aussi dans $_ENV pour getenv()
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Charger automatiquement au démarrage
loadEnv();
