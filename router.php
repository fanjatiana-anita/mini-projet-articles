<?php
/**
 * Routeur central pour les URLs propres frontend
 * Redirige les requêtes vers les bonnes vues sans changer le contexte de répertoire
 */

// Définir la racine du projet
define('ROOT', __DIR__);

// Récupérer la route demandée
$route = trim($_GET['_route'] ?? '', '/');

// Si route vide → page d'accueil
if (empty($route)) {
    require_once ROOT . '/app/Views/front/index.php';
    exit;
}

// Parser la route
if (preg_match('#^article/(\d+)-([a-z0-9\-]+)/?$#i', $route, $matches)) {
    // Route: /article/5-guerre-iran
    // matches[1] = ID (5)
    // matches[2] = slug (guerre-iran) — pour validation SEO
    $_GET['id'] = $matches[1];
    $_GET['slug'] = $matches[2];
    require_once ROOT . '/app/Views/front/article.php';
    exit;
}

if (preg_match('#^categorie/([a-z0-9\-]+)/?$#i', $route, $matches)) {
    // Route: /categorie/politique
    $_GET['slug'] = $matches[1];
    require_once ROOT . '/app/Views/front/categorie.php';
    exit;
}

if (preg_match('#^articles/?$#i', $route)) {
    // Route: /articles
    require_once ROOT . '/app/Views/front/articles.php';
    exit;
}

// Si aucune route ne correspond → 404
http_response_code(404);
require_once ROOT . '/app/Views/front/404.php';
exit;
