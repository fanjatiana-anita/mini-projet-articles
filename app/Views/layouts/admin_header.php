<?php
// Charger les variables d'environnement (TinyMCE API key, etc.)
require_once ROOT . '/config/env.php';

// Protection session — toutes les pages admin incluent ce fichier
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . adminUrl('login'));
    exit;
}

// Déterminer la page active pour le menu
$current_page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Panneau d'administration Iran News - Gestion des articles et catégories">
    <meta name="theme-color" content="#0d6efd">
    <title>BackOffice — Iran News</title>

    <!-- Préconnexion DNS pour améliorer les performances -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Bootstrap CSS avec SRI -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS personnalisés -->
    <link rel="preload" href="<?= BASE_URL ?>/public/css/admin.css" as="style">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
</head>
<body>
    <!-- Skip to main content - Accessibilité -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>

    <!-- Layout wrapper -->
    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar" role="navigation" aria-label="Navigation principale">
            <!-- Brand/Logo -->
            <a href="<?= adminUrl('dashboard') ?>" class="sidebar-brand">
                <i class="bi bi-newspaper" aria-hidden="true"></i>
                <span>Iran News <small>BO</small></span>
            </a>

            <!-- Menu principal -->
            <nav>
                <ul class="sidebar-menu" role="menu">
                    <li class="sidebar-menu-item" role="none">
                        <a
                            href="<?= adminUrl('dashboard') ?>"
                            class="sidebar-menu-link <?= $current_page === 'dashboard' ? 'active' : '' ?>"
                            role="menuitem"
                            aria-current="<?= $current_page === 'dashboard' ? 'page' : 'false' ?>">
                            <i class="bi bi-speedometer2" aria-hidden="true"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" role="none">
                        <a
                            href="<?= adminUrl('articles') ?>"
                            class="sidebar-menu-link <?= in_array($current_page, ['articles', 'article_add', 'article_edit']) ? 'active' : '' ?>"
                            role="menuitem"
                            aria-current="<?= in_array($current_page, ['articles', 'article_add', 'article_edit']) ? 'page' : 'false' ?>">
                            <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                            <span>Articles</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" role="none">
                        <a
                            href="<?= adminUrl('categories') ?>"
                            class="sidebar-menu-link <?= in_array($current_page, ['categories', 'category_add', 'category_edit']) ? 'active' : '' ?>"
                            role="menuitem"
                            aria-current="<?= in_array($current_page, ['categories', 'category_add', 'category_edit']) ? 'page' : 'false' ?>">
                            <i class="bi bi-folder" aria-hidden="true"></i>
                            <span>Catégories</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item" role="none">
                        <a
                            href="<?= BASE_URL ?>/"
                            class="sidebar-menu-link"
                            role="menuitem"
                            target="_blank"
                            rel="noopener noreferrer">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                            <span>Voir le site</span>
                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.875rem;" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Utilisateur et déconnexion -->
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar" aria-hidden="true">
                        <?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
                        <div class="sidebar-user-role">Administrateur</div>
                    </div>
                </div>
                <a href="<?= adminUrl('logout') ?>" class="sidebar-logout" aria-label="Se déconnecter">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Overlay pour mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

        <!-- Mobile toggle button -->
        <button
            class="sidebar-toggle"
            id="sidebarToggle"
            type="button"
            aria-label="Basculer la navigation"
            aria-expanded="false"
            aria-controls="adminSidebar">
            <i class="bi bi-list" style="font-size: 1.5rem;" aria-hidden="true"></i>
        </button>

        <!-- Main Content Area -->
        <div class="admin-content">
            <main class="admin-main" id="main-content" role="main">