<?php
/**
 * Fonctions utilitaires globales
 */

// Charger les variables d'environnement (.env) en premier
require_once __DIR__ . '/env.php';

// Déterminer l'URL de base du projet (utile lorsque le projet est dans un sous-dossier)
if (!defined('BASE_URL')) {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    // Retirer /index.php ou /router.php si présent
    $base = preg_replace('#/(index|router|admin)\\.php$#', '', $script);
    // Si vide, définir sur racine
    if ($base === '') $base = '/';
    define('BASE_URL', rtrim($base, '/'));
}

/**
 * Configuration TinyMCE API Key
 * À remplacer par votre clé API de https://www.tiny.cloud/
 * Pour développement local, "no-api-key" fonctionne avec des limitations
 */
if (!defined('TINYMCE_API_KEY')) {
    define('TINYMCE_API_KEY', getenv('TINYMCE_API_KEY') ?: 'no-api-key');
}

/**
 * Redirection HTTP
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Vérifier que l'utilisateur est admin
 * Sinon, redirection vers login
 */
function requireAdmin(): void {
    if (empty($_SESSION['admin_id'])) {
        redirect('/admin.php?page=login');
    }
}


/**
 * Générer un slug depuis un titre
 */
function generateSlug(string $title): string {
    $slug = mb_strtolower($title, 'UTF-8');
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Échapper une chaîne pour l'HTML
 */
function escape(mixed $value): string {
    if (is_array($value)) {
        return implode(', ', array_map('escape', $value));
    }
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Alias court pour escape()
 */
// function e(mixed $value): string {
//     return escape($value);
// }

/**
 * Afficher un message flash
 */
function getFlash(string $key = 'flash'): array {
    $flash = $_SESSION[$key] ?? null;
    unset($_SESSION[$key]);
    return $flash ?? [];
}

/**
 * Définir un message flash
 */
function setFlash(string $type, string $message, string $key = 'flash'): void {
    $_SESSION[$key] = ['type' => $type, 'message' => $message];
}

/**
 * Vérifier si une chaîne commence par une autre
 */
function startsWith(string $haystack, string $needle): bool {
    return strncmp($haystack, $needle, strlen($needle)) === 0;
}

/**
 * Vérifier si une chaîne finit par une autre
 */
function endsWith(string $haystack, string $needle): bool {
    if (empty($needle)) return true;
    return substr($haystack, -strlen($needle)) === $needle;
}

/**
 * Extraire un extrait du texte
 */
function excerpt(string $text, int $length = 150): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

/**
 * Formater une date
 */
function formatDate(string $date, string $format = 'd/m/Y'): string {
    if (empty($date)) return '';
    
    $timestamp = strtotime($date);
    return $timestamp ? date($format, $timestamp) : $date;
}

/**
 * Vérifier si une image existe localement
 */
function imageExists(string $path): bool {
    return file_exists(ROOT . '/public/' . ltrim($path, '/'));
}

/**
 * Récupérer l'URL d'une image
 */
function imageUrl(string $path): string {
    if (empty($path)) {
        return BASE_URL . '/public/images/placeholder.png';
    }
    return BASE_URL . '/public/' . ltrim($path, '/');
}

/**
 * Générer une URL d'article au format ID-SLUG (SEO optimisé)
 * Exemple: articleUrl(5, 'guerre-iran') → /article/5-guerre-iran
 */
function articleUrl(int $id, string $slug): string {
    return BASE_URL . '/article/' . $id . '-' . slugify($slug);
}

/**
 * Générer une URL de catégorie
 * Exemple: categoryUrl('politique') → /categorie/politique
 */
function categoryUrl(string $slug): string {
    return BASE_URL . '/categorie/' . slugify($slug);
}

/**
 * Normaliser un slug (lowercase, replace spaces with -, remove special chars)
 */
function slugify(string $text): string {
    // Minuscules
    $text = strtolower($text);
    // Remplacer les espaces par des tirets
    $text = preg_replace('/\s+/', '-', $text);
    // Supprimer caractères spéciaux
    $text = preg_replace('/[^\w\-]/', '', $text);
    // Supprimer tirets multiples
    $text = preg_replace('/-+/', '-', $text);
    // Supprimer tirets au début/fin
    return trim($text, '-');
}

/**
 * Générer une URL d'administration
 */
function adminUrl(string $page = 'dashboard', array $params = []): string {
    if (!defined('BASE_URL_ADMIN')) {
        // Si BASE_URL_ADMIN n'est pas défini, on le calcule à partir de BASE_URL
        define('BASE_URL_ADMIN', BASE_URL);
    }
    
    $url = BASE_URL_ADMIN . '/admin.php?page=' . urlencode($page);
    foreach ($params as $key => $value) {
        $url .= '&' . urlencode($key) . '=' . urlencode($value);
    }
    return $url;
}

/**
 * Upload une image dans public/images/
 * Renvoie le chemin relatif à public/ (ex: "images/article-2026.jpg")
 */
/**
 * Upload une image de manière sécurisée
 * Retourne le chemin relatif (ex: "images/abc123.jpg")
 */
function uploadImage(array $file, string $folder = 'images'): string {
    // Vérifier que le fichier existe et a un nom
    if (!isset($file['tmp_name']) || !$file['tmp_name'] || !$file['name']) {
        return '';
    }
    
    // Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return '';
    }
    
    // Vérifier l'extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed)) {
        return '';
    }
    
    // Vérifier la taille (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return '';
    }
    
    // Créer un nom unique basé sur la date et un hash
    $filename = date('Y-m-d_H-i-s') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $uploadDir = ROOT . '/public/' . $folder;
    
    // Créer le dossier s'il n'existe pas
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }
    
    // Chemin complet du fichier
    $uploadPath = $uploadDir . '/' . $filename;
    
    // Sauvegarder le fichier
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Retourner le chemin relatif pour la BD
        return $folder . '/' . $filename;
    }
    
    return '';
}


