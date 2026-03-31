<?php
/**
 * Vue FO — Catégorie
 * Variables : $categorie, $articles, $categories, $recents
 */
$pageTitle       = e($categorie['nom']) . ' – Articles | Iran News';
$pageDescription = 'Découvrez tous nos articles sur ' . $categorie['nom'] . '. Analyses et actualités sur l\'Iran.';
$pageKeywords    = 'Iran, ' . $categorie['nom'] . ', actualités Iran';

require_once ROOT . '/app/Views/layouts/front_header.php';
?>

<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/articles">Articles</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($categorie['nom']) ?></li>
        </ol>
    </div>
</nav>

<section class="bg-primary text-white py-4">
    <div class="container">
        <?php
        $icon = match($categorie['slug']) {
            'politique'  => 'bi-bank',
            'militaire'  => 'bi-shield',
            'diplomatie' => 'bi-globe',
            default      => 'bi-folder',
        };
        ?>
        <h1 class="display-6 fw-bold mb-2">
            <i class="bi <?= $icon ?> me-3"></i><?= e($categorie['nom']) ?>
        </h1>
        <p class="lead mb-0">
            <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> dans cette catégorie
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">

            <div class="col-lg-8">
                <?php if (empty($articles)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>Aucun article dans cette catégorie.
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($articles as $article): ?>
                            <div class="col-md-6 mb-4">
                                <article class="card h-100 border-0 shadow-sm">
                                    <?php if ($article['image_principale']): ?>
                                        <img src="<?= imageUrl($article['image_principale']) ?>"
                                             class="card-img-top"
                                             style="height:180px;object-fit:cover"
                                             alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
                                    <?php else: ?>
                                        <div class="bg-secondary d-flex align-items-center justify-content-center"
                                             style="height:180px">
                                            <i class="bi bi-image text-white display-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">
                                            <i class="bi bi-calendar me-1"></i><?= formaterDate($article['date_publication']) ?>
                                        </small>
                                        <h2 class="card-title h5">
                                            <a href="<?= articleUrl($article['id'], $article['slug']) ?>"
                                               class="text-decoration-none text-dark stretched-link">
                                                <?= e($article['titre']) ?>
                                            </a>
                                        </h2>
                                        <p class="card-text text-muted small">
                                            <?= e(genererExtrait($article['contenu'], 100)) ?>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-0">
                                        <span class="text-primary small fw-bold">
                                            Lire la suite <i class="bi bi-arrow-right"></i>
                                        </span>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3"><i class="bi bi-folder me-2"></i>Autres catégories</h2>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($categories as $cat):
                                if ($cat['slug'] === $categorie['slug']) continue; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <a href="<?= categorieUrl($cat['slug']) ?>" class="text-decoration-none">
                                        <?= e($cat['nom']) ?>
                                    </a>
                                    <span class="badge bg-primary rounded-pill"><?= $cat['nb_articles'] ?? 0 ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-clock-history me-2"></i>Derniers articles
                        </h2>
                        <ul class="list-unstyled">
                            <?php foreach ($recents as $recent): ?>
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="<?= articleUrl($recent['id'], $recent['slug']) ?>"
                                       class="text-decoration-none text-dark">
                                        <span class="badge bg-secondary me-1"><?= e($recent['categorie_nom']) ?></span>
                                        <small class="d-block text-muted mt-1">
                                            <?= formaterDate($recent['date_publication']) ?>
                                        </small>
                                        <?= e($recent['titre']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT . '/app/Views/layouts/front_footer.php'; ?>
