<?php
/**
 * Routeur FrontOffice — router.php
 */

// ROOT défini ici une seule fois — index.php ne doit pas le redéfinir
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}

require_once ROOT . '/config/database.php';
require_once ROOT . '/config/helpers.php';
require_once ROOT . '/app/Views/includes/functions.php';

$uri = '/' . trim($_GET['_route'] ?? '', '/');

// ── ACCUEIL ──────────────────────────────────────────────────
if ($uri === '/') {
    $derniersArticles = getArticlesPublies(6);
    $categories       = getCategoriesAvecNb();
    require ROOT . '/app/Views/front/index.php';
    exit;
}

// ── LISTE / RECHERCHE  →  /articles ─────────────────────────
if (preg_match('#^/articles/?$#', $uri)) {
    $recherche  = trim($_GET['q'] ?? '');
    $articles   = !empty($recherche)
        ? rechercherArticles($recherche)
        : getArticlesPublies();
    $categories = getCategoriesAvecNb();
    $recents    = getArticlesPublies(5);
    require ROOT . '/app/Views/front/articles.php';
    exit;
}

// ── DÉTAIL ARTICLE  →  /article/{id}-{slug} ─────────────────
if (preg_match('#^/article/(\d+)-([a-z0-9\-]+)/?$#i', $uri, $m)) {
    $article = getArticleById((int)$m[1]);

    if (!$article) {
        http_response_code(404);
        require ROOT . '/app/Views/front/404.php';
        exit;
    }

    // Redirection canonique si slug différent
    if ($article['slug'] !== $m[2]) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . BASE_URL . '/article/' . $article['id'] . '-' . $article['slug']);
        exit;
    }

    $tousSimil = getArticlesByCategorie($article['categorie_slug']);
    $articlesSimilaires = array_slice(
        array_filter($tousSimil, fn($a) => $a['id'] !== $article['id']),
        0, 3
    );
    $categories = getCategoriesAvecNb();
    $recents    = getArticlesPublies(5);
    require ROOT . '/app/Views/front/article.php';
    exit;
}

// ── CATÉGORIE  →  /categorie/{slug} ─────────────────────────
if (preg_match('#^/categorie/([a-z0-9\-]+)/?$#i', $uri, $m)) {
    $categorie = getCategorieBySlug($m[1]);

    if (!$categorie) {
        http_response_code(404);
        require ROOT . '/app/Views/front/404.php';
        exit;
    }

    $articles   = getArticlesByCategorie($m[1]);
    $categories = getCategoriesAvecNb();
    $recents    = getArticlesPublies(5);
    require ROOT . '/app/Views/front/categorie.php';
    exit;
}

// ── 404 ──────────────────────────────────────────────────────
http_response_code(404);
require ROOT . '/app/Views/front/404.php';
