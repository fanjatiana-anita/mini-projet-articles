<?php
/**
 * Vue FO — Détail article
 * Variables : $article, $articlesSimilaires, $categories, $recents
 */
$pageTitle       = e($article['titre']) . ' | Iran News';
$pageDescription = $article['meta_description'] ?? genererExtrait($article['contenu'], 160);
$pageKeywords    = 'Iran, ' . $article['categorie_nom'] . ', actualités Iran';

require_once ROOT . '/app/Views/layouts/front_header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/articles">Articles</a></li>
            <li class="breadcrumb-item">
                <a href="<?= categorieUrl($article['categorie_slug']) ?>">
                    <?= e($article['categorie_nom']) ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($article['titre']) ?></li>
        </ol>
    </div>
</nav>

<article class="py-5">
    <div class="container">
        <div class="row">

            <!-- Contenu principal -->
            <div class="col-lg-8">
                <header class="mb-4">
                    <span class="badge bg-primary mb-3"><?= e($article['categorie_nom']) ?></span>
                    <h1 class="display-5 fw-bold mb-3"><?= e($article['titre']) ?></h1>
                    <div class="d-flex align-items-center text-muted mb-4 gap-4">
                        <span>
                            <i class="bi bi-calendar me-1"></i>
                            <?= formaterDate($article['date_publication']) ?>
                        </span>
                        <span>
                            <i class="bi bi-folder me-1"></i>
                            <a href="<?= categorieUrl($article['categorie_slug']) ?>"
                               class="text-muted text-decoration-none">
                                <?= e($article['categorie_nom']) ?>
                            </a>
                        </span>
                    </div>
                </header>

                <?php if ($article['image_principale']): ?>
                    <figure class="mb-4">
                        <img src="<?= imageUrl($article['image_principale']) ?>"
                             class="img-fluid rounded shadow-sm w-100"
                             alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
                        <?php if ($article['alt_image']): ?>
                            <figcaption class="text-muted small mt-2 text-center">
                                <?= e($article['alt_image']) ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>

                <div class="article-content">
                    <?= $article['contenu'] ?>
                </div>

                <!-- Partage -->
                <div class="border-top border-bottom py-4 my-4">
                    <h2 class="h6 fw-bold mb-3">Partager</h2>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/article/' . $article['id'] . '-' . $article['slug']) ?>"
                           class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener"
                           title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '/article/' . $article['id'] . '-' . $article['slug']) ?>&text=<?= urlencode($article['titre']) ?>"
                           class="btn btn-outline-info btn-sm" target="_blank" rel="noopener"
                           title="Twitter"><i class="bi bi-twitter"></i></a>
                        <a href="https://wa.me/?text=<?= urlencode($article['titre'] . ' ' . BASE_URL . '/article/' . $article['id'] . '-' . $article['slug']) ?>"
                           class="btn btn-outline-success btn-sm" target="_blank" rel="noopener"
                           title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <nav class="d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>/articles" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Tous les articles
                    </a>
                    <a href="<?= categorieUrl($article['categorie_slug']) ?>" class="btn btn-outline-primary">
                        Plus de <?= e($article['categorie_nom']) ?>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </nav>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">

                <?php if (!empty($articlesSimilaires)): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h5 fw-bold mb-3">
                                <i class="bi bi-newspaper me-2"></i>Articles similaires
                            </h2>
                            <?php foreach ($articlesSimilaires as $sim): ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <h3 class="h6 mb-1">
                                        <a href="<?= articleUrl($sim['id'], $sim['slug']) ?>"
                                           class="text-decoration-none text-dark">
                                            <?= e($sim['titre']) ?>
                                        </a>
                                    </h3>
                                    <small class="text-muted">
                                        <?= formaterDate($sim['date_publication']) ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-folder me-2"></i>Catégories
                        </h2>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($categories as $cat): ?>
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
                            <?php foreach ($recents as $recent):
                                if ($recent['id'] === $article['id']) continue; ?>
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="<?= articleUrl($recent['id'], $recent['slug']) ?>"
                                       class="text-decoration-none text-dark">
                                        <small class="d-block text-muted">
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
</article>

<?php require_once ROOT . '/app/Views/layouts/front_footer.php'; ?>
