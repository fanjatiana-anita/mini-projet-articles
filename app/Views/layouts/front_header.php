<?php
/**
 * Layout FrontOffice — Header
 * Variables attendues (définies avant l'include) :
 *   $pageTitle, $pageDescription, $pageKeywords
 */
require_once ROOT . '/app/Views/includes/functions.php';

$pageTitle       = $pageTitle       ?? 'Iran News – Actualités sur la situation en Iran';
$pageDescription = $pageDescription ?? 'Suivez l\'actualité sur l\'Iran : politique, militaire et diplomatie.';
$pageKeywords    = $pageKeywords    ?? 'Iran, actualités Iran, guerre Iran, politique iranienne';

$navCategories = getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="keywords"    content="<?= e($pageKeywords) ?>">
    <meta name="author"      content="Iran News">
    <meta name="robots"      content="index, follow">
    <title><?= e($pageTitle) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">
            <i class="bi bi-newspaper me-2"></i>Iran News
        </a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/articles">Tous les articles</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">Catégories</a>
                    <ul class="dropdown-menu">
                        <?php foreach ($navCategories as $cat): ?>
                            <li>
                                <a class="dropdown-item"
                                   href="<?= BASE_URL ?>/categorie/<?= e($cat['slug']) ?>">
                                    <?= e($cat['nom']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            <form class="d-flex me-2" role="search"
                  action="<?= BASE_URL ?>/articles" method="get">
                <input class="form-control me-2" type="search" name="q"
                       placeholder="Rechercher…" aria-label="Rechercher"
                       value="<?= isset($recherche) ? e($recherche) : '' ?>">
                <button class="btn btn-outline-light" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <a href="<?= BASE_URL ?>/admin.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-person-circle me-1"></i>Admin
            </a>
        </div>
    </div>
</nav>

<main>
