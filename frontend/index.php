<?php
/**
 * Page d'accueil - Iran News
 * Affiche les derniers articles et une présentation du site
 */

// Configuration SEO de la page
$pageTitle = 'Iran News - Actualités et analyses sur la situation en Iran';
$pageDescription = 'Suivez toute l\'actualité sur la situation en Iran : politique, militaire et diplomatie. Articles de fond et analyses sur le conflit iranien.';
$pageKeywords = 'Iran, actualités Iran, guerre Iran, conflit Iran, politique iranienne, sanctions Iran, nucléaire Iran';

require_once 'includes/header.php';

// Récupérer les 6 derniers articles
$derniersArticles = getArticlesPublies(6);
$categories = getCategories();
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Iran News</h1>
                <p class="lead mb-4">
                    Votre source d'information fiable sur la situation en Iran.
                    Analyses politiques, couverture militaire et actualités diplomatiques.
                </p>
                <a href="articles.php" class="btn btn-light btn-lg">
                    <i class="bi bi-newspaper me-2"></i>Voir tous les articles
                </a>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                <i class="bi bi-globe-central-south-asia display-1"></i>
            </div>
        </div>
    </div>
</section>

<!-- Derniers articles -->
<section class="py-5">
    <div class="container">
        <h2 class="h3 fw-bold mb-4 border-bottom pb-2">
            <i class="bi bi-clock-history me-2"></i>Derniers articles
        </h2>

        <?php if (empty($derniersArticles)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Aucun article publié pour le moment.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($derniersArticles as $index => $article): ?>
                    <?php if ($index === 0): ?>
                        <!-- Article principal (featured) -->
                        <div class="col-lg-8 mb-4">
                            <article class="card h-100 border-0 shadow-sm featured-article">
                                <?php if ($article['image_principale']): ?>
                                    <img src="<?= e($article['image_principale']) ?>"
                                         class="card-img-top featured-img"
                                         alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
                                <?php else: ?>
                                    <div class="card-img-top featured-img bg-secondary d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-white display-1"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2"><?= e($article['categorie_nom']) ?></span>
                                    <h2 class="card-title h4">
                                        <a href="article.php?slug=<?= e($article['slug']) ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= e($article['titre']) ?>
                                        </a>
                                    </h2>
                                    <p class="card-text text-muted">
                                        <?= e(genererExtrait($article['contenu'], 200)) ?>
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i><?= formaterDate($article['date_publication']) ?>
                                    </small>
                                </div>
                            </article>
                        </div>

                        <!-- Sidebar avec les articles suivants -->
                        <div class="col-lg-4">
                    <?php else: ?>
                            <!-- Articles secondaires -->
                            <article class="card mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <span class="badge bg-secondary mb-2"><?= e($article['categorie_nom']) ?></span>
                                    <h2 class="card-title h6">
                                        <a href="article.php?slug=<?= e($article['slug']) ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= e($article['titre']) ?>
                                        </a>
                                    </h2>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i><?= formaterDate($article['date_publication']) ?>
                                    </small>
                                </div>
                            </article>
                        <?php if ($index === count($derniersArticles) - 1 || $index === 5): ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Catégories -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="h3 fw-bold mb-4 border-bottom pb-2">
            <i class="bi bi-folder me-2"></i>Explorer par catégorie
        </h2>

        <div class="row">
            <?php foreach ($categories as $categorie): ?>
                <div class="col-md-4 mb-4">
                    <a href="categorie.php?slug=<?= e($categorie['slug']) ?>" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 category-card">
                            <div class="card-body text-center py-4">
                                <?php
                                $icon = 'bi-folder';
                                if ($categorie['slug'] === 'politique') $icon = 'bi-bank';
                                elseif ($categorie['slug'] === 'militaire') $icon = 'bi-shield';
                                elseif ($categorie['slug'] === 'diplomatie') $icon = 'bi-globe';
                                ?>
                                <i class="bi <?= $icon ?> display-4 text-primary mb-3"></i>
                                <h3 class="card-title h5 text-dark"><?= e($categorie['nom']) ?></h3>
                                <p class="card-text text-muted small">
                                    Découvrir les articles
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- À propos -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="h3 fw-bold mb-3">À propos d'Iran News</h2>
                <p class="text-muted">
                    Iran News est une plateforme d'information dédiée à l'analyse de la situation
                    en Iran. Notre équipe de rédacteurs suit de près les développements politiques,
                    militaires et diplomatiques pour vous offrir une couverture complète et objective.
                </p>
                <p class="text-muted">
                    Nous nous engageons à fournir des informations vérifiées et des analyses
                    approfondies pour vous aider à comprendre les enjeux complexes de cette région.
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3">
                            <span class="display-4 fw-bold text-primary"><?= count($derniersArticles) ?>+</span>
                            <p class="text-muted mb-0">Articles</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3">
                            <span class="display-4 fw-bold text-primary"><?= count($categories) ?></span>
                            <p class="text-muted mb-0">Catégories</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3">
                            <span class="display-4 fw-bold text-primary">24/7</span>
                            <p class="text-muted mb-0">Disponible</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
