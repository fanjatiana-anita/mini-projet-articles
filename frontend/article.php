<?php
/**
 * Page détail article - Iran News
 * Affiche le contenu complet d'un article
 */

require_once 'includes/functions.php';

// Récupérer le slug depuis l'URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: articles.php');
    exit;
}

// Récupérer l'article
$article = getArticleBySlug($slug);

if (!$article) {
    // Article non trouvé - page 404
    http_response_code(404);
    $pageTitle = 'Article non trouvé | Iran News';
    $pageDescription = 'L\'article que vous recherchez n\'existe pas ou a été supprimé.';
    require_once 'includes/header.php';
    ?>
    <section class="py-5">
        <div class="container text-center">
            <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
            <h1 class="h2 mt-4">Article non trouvé</h1>
            <p class="text-muted">L'article que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="articles.php" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Retour aux articles
            </a>
        </div>
    </section>
    <?php
    require_once 'includes/footer.php';
    exit;
}

// Configuration SEO de la page avec les données de l'article
$pageTitle = $article['titre'] . ' | Iran News';
$pageDescription = $article['meta_description'] ?? genererExtrait($article['contenu'], 160);
$pageKeywords = 'Iran, ' . $article['categorie_nom'] . ', actualités Iran';

require_once 'includes/header.php';

// Récupérer les articles similaires (même catégorie)
$articlesSimilaires = getArticlesByCategorie($article['categorie_slug']);
$articlesSimilaires = array_filter($articlesSimilaires, function($a) use ($article) {
    return $a['id'] !== $article['id'];
});
$articlesSimilaires = array_slice($articlesSimilaires, 0, 3);
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item"><a href="articles.php">Articles</a></li>
            <li class="breadcrumb-item">
                <a href="categorie.php?slug=<?= e($article['categorie_slug']) ?>">
                    <?= e($article['categorie_nom']) ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($article['titre']) ?></li>
        </ol>
    </div>
</nav>

<!-- Article -->
<article class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <!-- En-tête de l'article -->
                <header class="mb-4">
                    <span class="badge bg-primary mb-3"><?= e($article['categorie_nom']) ?></span>
                    <h1 class="display-5 fw-bold mb-3"><?= e($article['titre']) ?></h1>
                    <div class="d-flex align-items-center text-muted mb-4">
                        <span class="me-4">
                            <i class="bi bi-calendar me-1"></i>
                            <?= formaterDate($article['date_publication']) ?>
                        </span>
                        <span>
                            <i class="bi bi-folder me-1"></i>
                            <a href="categorie.php?slug=<?= e($article['categorie_slug']) ?>" class="text-muted text-decoration-none">
                                <?= e($article['categorie_nom']) ?>
                            </a>
                        </span>
                    </div>
                </header>

                <!-- Image principale -->
                <?php if ($article['image_principale']): ?>
                    <figure class="mb-4">
                        <img src="<?= e($article['image_principale']) ?>"
                             class="img-fluid rounded shadow-sm w-100"
                             alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
                        <?php if ($article['alt_image']): ?>
                            <figcaption class="text-muted small mt-2 text-center">
                                <?= e($article['alt_image']) ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>

                <!-- Contenu de l'article -->
                <div class="article-content">
                    <?= $article['contenu'] ?>
                </div>

                <!-- Partage social -->
                <div class="border-top border-bottom py-4 my-4">
                    <h2 class="h6 fw-bold mb-3">Partager cet article</h2>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm" title="Partager sur Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm" title="Partager sur Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm" title="Partager sur WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm" title="Partager sur LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigation entre articles -->
                <nav class="d-flex justify-content-between py-3">
                    <a href="articles.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Tous les articles
                    </a>
                    <a href="categorie.php?slug=<?= e($article['categorie_slug']) ?>" class="btn btn-outline-primary">
                        Plus de <?= e($article['categorie_nom']) ?><i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </nav>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Articles similaires -->
                <?php if (!empty($articlesSimilaires)): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="h5 fw-bold mb-3">
                                <i class="bi bi-newspaper me-2"></i>Articles similaires
                            </h2>
                            <?php foreach ($articlesSimilaires as $similaire): ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <h3 class="h6 mb-1">
                                        <a href="article.php?slug=<?= e($similaire['slug']) ?>" class="text-decoration-none text-dark">
                                            <?= e($similaire['titre']) ?>
                                        </a>
                                    </h3>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i><?= formaterDate($similaire['date_publication']) ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Catégories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-folder me-2"></i>Catégories
                        </h2>
                        <ul class="list-group list-group-flush">
                            <?php
                            $categories = getCategories();
                            foreach ($categories as $cat):
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <a href="categorie.php?slug=<?= e($cat['slug']) ?>" class="text-decoration-none">
                                        <?= e($cat['nom']) ?>
                                    </a>
                                    <span class="badge bg-primary rounded-pill">
                                        <?php
                                        $articlesCategorie = getArticlesByCategorie($cat['slug']);
                                        echo count($articlesCategorie);
                                        ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Derniers articles -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-clock-history me-2"></i>Derniers articles
                        </h2>
                        <ul class="list-unstyled">
                            <?php
                            $recents = getArticlesPublies(5);
                            foreach ($recents as $recent):
                                if ($recent['id'] !== $article['id']):
                            ?>
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="article.php?slug=<?= e($recent['slug']) ?>" class="text-decoration-none text-dark">
                                        <small class="d-block text-muted">
                                            <?= formaterDate($recent['date_publication']) ?>
                                        </small>
                                        <?= e($recent['titre']) ?>
                                    </a>
                                </li>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<?php require_once 'includes/footer.php'; ?>
