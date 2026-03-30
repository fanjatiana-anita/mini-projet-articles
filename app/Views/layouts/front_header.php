<?php
// inclure les fonctions locales aux vues (chemin relatif correct depuis layouts/)
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = $pageTitle ?? 'Iran News — Actualités sur la guerre en Iran';
$pageDescription = $pageDescription ?? 'Suivez toute l\'actualité sur la situation en Iran.';
$pageKeywords = $pageKeywords ?? 'Iran, actualités Iran, guerre Iran, politique iranienne';
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
 <meta name="keywords" content="<?= htmlspecialchars($pageKeywords) ?>">
 <meta name="robots" content="index, follow">
 <title><?= htmlspecialchars($pageTitle) ?></title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
 <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
 <div class="container">
 <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">Iran News</a>
 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
 <span class="navbar-toggler-icon"></span>
 </button>
 <div class="collapse navbar-collapse" id="nav">
 <ul class="navbar-nav me-auto">
 <li class="nav-item">
 <a class="nav-link" href="<?= BASE_URL ?>/">Accueil</a>
 </li>
 <li class="nav-item">
 <a class="nav-link" href="<?= BASE_URL ?>/articles">Articles</a>
 </li>
 <li class="nav-item dropdown">
 <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Catégories</a>
 <ul class="dropdown-menu">
 <?php foreach ($categories as $cat): ?>
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

 <a href="<?= BASE_URL ?>/admin.php?page=login" class="btn btn-outline-light btn-sm">
 <i class="bi bi-person-circle"></i> Connexion
 </a>
 </div>
 </div>
</nav>
<main>