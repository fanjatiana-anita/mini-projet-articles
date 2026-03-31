<?php
// ============================================================
//  admin.php — routeur central du BackOffice
//  Toutes les URLs du BO passent par ici via ?page=xxx
//  Ex: admin.php?page=login
//      admin.php?page=articles
//      admin.php?page=articles&action=create
// ============================================================

// Définir ROOT du projet
define('ROOT', __DIR__);

// Charger les helpers (y compris adminUrl, BASE_URL, etc.)
require_once ROOT . '/config/helpers.php';

require_once ROOT . '/config/database.php';
$pdo = getConnection();

// Définir BASE_URL_ADMIN pour le backoffice
if (!defined('BASE_URL_ADMIN')) {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $base = preg_replace('#/admin\\.php$#', '', $script);
    if ($base === '') $base = '/';
    define('BASE_URL_ADMIN', rtrim($base, '/'));
}

session_start();

$page   = $_GET['page']   ?? 'dashboard';

// ============================================================
// ROUTE UPLOAD IMAGE (TinyMCE)
// ============================================================
if ($page === 'upload-image') {

    header('Content-Type: application/json');

    // Vérifier connexion (sécurité)
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Non autorisé']);
        exit;
    }

    if (empty($_FILES['file']['tmp_name'])) {
        echo json_encode(['error' => 'Aucun fichier reçu']);
        exit;
    }

    $file = $_FILES['file'];

    // Vérifier taille
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['error' => 'Fichier trop lourd']);
        exit;
    }

    // Vérifier MIME
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);

    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed)) {
        echo json_encode(['error' => 'Format non autorisé']);
        exit;
    }

    // Extension
    $ext = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ][$mime];

    // Nom unique
    $filename = date('Y-m-d') . '_' . uniqid() . '.' . $ext;

    $uploadDir = ROOT . '/public/images/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        echo json_encode(['error' => 'Erreur sauvegarde']);
        exit;
    }

    echo json_encode([
        'location' => BASE_URL . '/public/images/' . $filename
    ]);
    exit;
}

$action = $_GET['action'] ?? 'index';

// Pages accessibles sans être connecté
$public = ['login', 'logout'];

// Vérifier la session sauf pour login/logout
if (!in_array($page, $public) && !isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL_ADMIN . '/admin.php?page=login');
    exit;
}

// Router vers la bonne vue
$views = [
    'login'      => ROOT . '/app/Views/admin/login.php',
    'logout'     => ROOT . '/app/Views/admin/logout.php',
    'dashboard'  => ROOT . '/app/Views/admin/dashboard.php',
    'articles'   => ROOT . '/app/Views/admin/articles/index.php',
    'article_create' => ROOT . '/app/Views/admin/articles/create.php',
    'article_edit'   => ROOT . '/app/Views/admin/articles/edit.php',
    'article_delete' => ROOT . '/app/Views/admin/articles/delete.php',
    'categories' => ROOT . '/app/Views/admin/categories/index.php',
];

// Construire la clé de routage
$key = $page;
if ($page === 'articles' && $action !== 'index') {
    $key = 'article_' . $action;
}

$view = $views[$key] ?? $views['dashboard'];

if (file_exists($view)) {
    require $view;
} else {
    echo "<p>Page introuvable : $key</p>";
}


