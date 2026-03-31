<?php
/**
 * Vue FO — Accueil
 * Variables : $derniersArticles, $categories
 */
$pageTitle       = 'Iran News – Actualités et analyses sur la situation en Iran';
$pageDescription = 'Suivez toute l\'actualité sur la situation en Iran : politique, militaire et diplomatie.';
$pageKeywords    = 'Iran, actualités Iran, guerre Iran, conflit Iran, politique iranienne, sanctions Iran';

require_once ROOT . '/app/Views/layouts/front_header.php';
?>

<!-- Hero -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Iran News</h1>
                <p class="lead mb-4">
                    Votre source d'information fiable sur la situation en Iran.
                    Analyses politiques, couverture militaire et actualités diplomatiques.
                </p>
                <a href="<?= BASE_URL ?>/articles" class="btn btn-light btn-lg">
                    <i class="bi bi-newspaper me-2"></i>Voir tous les articles
                </a>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                <i class="bi bi-globe-central-south-asia display-1 opacity-75"></i>
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
                        <!-- Article featured -->
                        <div class="col-lg-8 mb-4">
                            <article class="card h-100 border-0 shadow-sm">
                                <?php if ($article['image_principale']): ?>
                                    <img src="<?= imageUrl($article['image_principale']) ?>"
                                         class="card-img-top"
                                         style="height:320px;object-fit:cover"
                                         alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
                                <?php else: ?>
                                    <div class="bg-secondary d-flex align-items-center justify-content-center"
                                         style="height:320px">
                                        <i class="bi bi-image text-white display-1"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2"><?= e($article['categorie_nom']) ?></span>
                                    <h2 class="card-title h4">
                                        <a href="<?= articleUrl($article['id'], $article['slug']) ?>"
                                           class="text-decoration-none text-dark stretched-link">
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
                        <div class="col-lg-4">

                    <?php else: ?>
                        <!-- Articles secondaires -->
                        <article class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <span class="badge bg-secondary mb-2"><?= e($article['categorie_nom']) ?></span>
                                <h2 class="card-title h6">
                                    <a href="<?= articleUrl($article['id'], $article['slug']) ?>"
                                       class="text-decoration-none text-dark stretched-link">
                                        <?= e($article['titre']) ?>
                                    </a>
                                </h2>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i><?= formaterDate($article['date_publication']) ?>
                                </small>
                            </div>
                        </article>
                        <?php if ($index === count($derniersArticles) - 1 || $index === 5): ?>
                        </div><!-- /col-lg-4 -->
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
                    <a href="<?= categorieUrl($categorie['slug']) ?>" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 text-center py-4">
                            <div class="card-body">
                                <?php
                                $icon = match($categorie['slug']) {
                                    'politique'  => 'bi-bank',
                                    'militaire'  => 'bi-shield',
                                    'diplomatie' => 'bi-globe',
                                    default      => 'bi-folder',
                                };
                                ?>
                                <i class="bi <?= $icon ?> display-4 text-primary mb-3"></i>
                                <h3 class="card-title h5 text-dark"><?= e($categorie['nom']) ?></h3>
                                <p class="card-text text-muted small">
                                    <?= $categorie['nb_articles'] ?? '' ?> article<?= ($categorie['nb_articles'] ?? 0) > 1 ? 's' : '' ?>
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
                    en Iran. Notre équipe suit de près les développements politiques, militaires
                    et diplomatiques pour vous offrir une couverture complète et objective.
                </p>
            </div>
            <div class="col-lg-6">
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

<?php require_once ROOT . '/app/Views/layouts/front_footer.php'; ?>
