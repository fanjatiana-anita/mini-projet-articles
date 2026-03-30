<?php
/**
 * Page catégorie - Iran News
 * Affiche tous les articles d'une catégorie spécifique
 */

require_once 'includes/functions.php';

// Récupérer le slug de la catégorie
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: articles.php');
    exit;
}

// Récupérer la catégorie
$categorie = getCategorieBySlug($slug);

if (!$categorie) {
    // Catégorie non trouvée - page 404
    http_response_code(404);
    $pageTitle = 'Catégorie non trouvée | Iran News';
    $pageDescription = 'La catégorie que vous recherchez n\'existe pas.';
    require_once 'includes/header.php';
    ?>
    <section class="py-5">
        <div class="container text-center">
            <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
            <h1 class="h2 mt-4">Catégorie non trouvée</h1>
            <p class="text-muted">La catégorie que vous recherchez n'existe pas.</p>
            <a href="articles.php" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Voir tous les articles
            </a>
        </div>
    </section>
    <?php
    require_once 'includes/footer.php';
    exit;
}

// Récupérer les articles de la catégorie
$articles = getArticlesByCategorie($slug);

// Configuration SEO de la page
$pageTitle = $categorie['nom'] . ' - Articles | Iran News';
$pageDescription = 'Découvrez tous nos articles sur la catégorie ' . $categorie['nom'] . '. Analyses et actualités sur l\'Iran.';
$pageKeywords = 'Iran, ' . $categorie['nom'] . ', actualités Iran, articles ' . $categorie['nom'];

require_once 'includes/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item"><a href="articles.php">Articles</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($categorie['nom']) ?></li>
        </ol>
    </div>
</nav>

<!-- En-tête de la catégorie -->
<section class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <?php
                $icon = 'bi-folder';
                if ($slug === 'politique') $icon = 'bi-bank';
                elseif ($slug === 'militaire') $icon = 'bi-shield';
                elseif ($slug === 'diplomatie') $icon = 'bi-globe';
                ?>
                <h1 class="display-6 fw-bold mb-2">
                    <i class="bi <?= $icon ?> me-3"></i><?= e($categorie['nom']) ?>
                </h1>
                <p class="lead mb-0">
                    <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> dans cette catégorie
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Liste des articles -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Articles -->
            <div class="col-lg-8">
                <?php if (empty($articles)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Aucun article dans cette catégorie pour le moment.
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($articles as $article): ?>
                            <div class="col-md-6 mb-4">
                                <article class="card h-100 border-0 shadow-sm article-card">
                                    <?php if ($article['image_principale']): ?>
                                        <img src="<?= e($article['image_principale']) ?>"
                                             class="card-img-top"
                                             alt="<?= e($article['alt_image'] ?? $article['titre']) ?>"
                                             style="height: 180px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                                            <i class="bi bi-image text-white display-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">
                                            <i class="bi bi-calendar me-1"></i><?= formaterDate($article['date_publication']) ?>
                                        </small>
                                        <h2 class="card-title h5">
                                            <a href="article.php?slug=<?= e($article['slug']) ?>" class="text-decoration-none text-dark stretched-link">
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

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Autres catégories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-folder me-2"></i>Autres catégories
                        </h2>
                        <ul class="list-group list-group-flush">
                            <?php
                            $categories = getCategories();
                            foreach ($categories as $cat):
                                if ($cat['slug'] !== $slug):
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
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- Derniers articles (toutes catégories) -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-clock-history me-2"></i>Derniers articles
                        </h2>
                        <ul class="list-unstyled">
                            <?php
                            $recents = getArticlesPublies(5);
                            foreach ($recents as $recent):
                            ?>
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="article.php?slug=<?= e($recent['slug']) ?>" class="text-decoration-none text-dark">
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

<?php require_once 'includes/footer.php'; ?>
