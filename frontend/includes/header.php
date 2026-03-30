<?php
/**
 * En-tête du site - Header
 * Variables attendues :
 * - $pageTitle : Titre de la page
 * - $pageDescription : Meta description
 * - $pageKeywords : Meta keywords (optionnel)
 */

require_once __DIR__ . '/functions.php';

// Valeurs par défaut
$pageTitle = $pageTitle ?? 'Iran News - Actualités sur la situation en Iran';
$pageDescription = $pageDescription ?? 'Suivez l\'actualité sur la situation en Iran : politique, militaire et diplomatie. Analyses et articles de fond.';
$pageKeywords = $pageKeywords ?? 'Iran, actualités Iran, guerre Iran, politique iranienne, diplomatie Iran, situation Iran';

// Récupérer les catégories pour le menu
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="keywords" content="<?= e($pageKeywords) ?>">
    <meta name="author" content="Iran News">
    <meta name="robots" content="index, follow">

    <title><?= e($pageTitle) ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS personnalisé -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-newspaper me-2"></i>Iran News
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="articles.php">Tous les articles</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Catégories
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a class="dropdown-item" href="categorie.php?slug=<?= e($cat['slug']) ?>">
                                        <?= e($cat['nom']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" role="search" action="articles.php" method="get">
                    <input class="form-control me-2" type="search" name="q" placeholder="Rechercher..." aria-label="Rechercher">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
