<?php
// Charger les variables d'environnement (TinyMCE API key, etc.)
require_once ROOT . '/config/env.php';

// Protection session — toutes les pages admin incluent ce fichier
if (!isset($_SESSION['user_id'])) {
 header('Location: ' . adminUrl('login'));
 exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>BackOffice — Iran News</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
 <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark px-4">
 <a class="navbar-brand fw-bold" href="<?= adminUrl('dashboard') ?>">Iran News — BO</a>
 <div class="d-flex gap-2">
 <a href="<?= adminUrl('articles') ?>" class="btn btn-sm btn-outline-light">Articles</a>
 <a href="<?= adminUrl('categories') ?>" class="btn btn-sm btn-outline-light">Catégories</a>
 <span class="btn btn-sm btn-outline-secondary disabled"><?= htmlspecialchars($_SESSION['username']) ?></span>
 <a href="<?= adminUrl('logout') ?>" class="btn btn-sm btn-danger">Déconnexion</a>
 </div>
</nav>
<div class="container mt-4">