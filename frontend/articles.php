<?php
/**
 * Page liste des articles - Iran News
 * Affiche tous les articles publiés avec possibilité de recherche
 */

require_once 'includes/functions.php';

// Gestion de la recherche
$recherche = isset($_GET['q']) ? trim($_GET['q']) : '';

// Récupérer les articles
if (!empty($recherche)) {
    // Recherche dans les articles
    $pdo = getConnection();
    $sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts s ON a.statut_id = s.id
            WHERE s.libelle = 'publie'
            AND (a.titre LIKE :search OR a.contenu LIKE :search OR a.meta_description LIKE :search)
            ORDER BY a.date_publication DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search' => '%' . $recherche . '%']);
    $articles = $stmt->fetchAll();

    $pageTitle = 'Recherche : ' . $recherche . ' | Iran News';
    $pageDescription = 'Résultats de recherche pour "' . $recherche . '" sur Iran News.';
} else {
    $articles = getArticlesPublies();
    $pageTitle = 'Tous les articles | Iran News';
    $pageDescription = 'Consultez tous nos articles sur la situation en Iran : politique, militaire et diplomatie.';
}

$pageKeywords = 'articles Iran, actualités Iran, guerre Iran, politique iranienne, sanctions Iran';

require_once 'includes/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= !empty($recherche) ? 'Recherche' : 'Tous les articles' ?>
            </li>
        </ol>
    </div>
</nav>

<!-- Contenu principal -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Liste des articles -->
            <div class="col-lg-8">
                <h1 class="h2 fw-bold mb-4 border-bottom pb-2">
                    <?php if (!empty($recherche)): ?>
                        <i class="bi bi-search me-2"></i>Résultats pour "<?= e($recherche) ?>"
                    <?php else: ?>
                        <i class="bi bi-newspaper me-2"></i>Tous les articles
                    <?php endif; ?>
                </h1>

                <?php if (empty($articles)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <?php if (!empty($recherche)): ?>
                            Aucun article ne correspond à votre recherche "<?= e($recherche) ?>".
                        <?php else: ?>
                            Aucun article publié pour le moment.
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4">
                        <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> trouvé<?= count($articles) > 1 ? 's' : '' ?>
                    </p>

                    <?php foreach ($articles as $article): ?>
                        <article class="card mb-4 border-0 shadow-sm article-card">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <?php if ($article['image_principale']): ?>
                                        <img src="<?= e($article['image_principale']) ?>"
                                             class="img-fluid rounded-start h-100 object-fit-cover"
                                             alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
                                    <?php else: ?>
                                        <div class="bg-secondary h-100 d-flex align-items-center justify-content-center rounded-start" style="min-height: 200px;">
                                            <i class="bi bi-image text-white display-4"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-primary"><?= e($article['categorie_nom']) ?></span>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i><?= formaterDate($article['date_publication']) ?>
                                            </small>
                                        </div>
                                        <h2 class="card-title h5">
                                            <a href="article.php?slug=<?= e($article['slug']) ?>" class="text-decoration-none text-dark stretched-link">
                                                <?= e($article['titre']) ?>
                                            </a>
                                        </h2>
                                        <p class="card-text text-muted">
                                            <?= e(genererExtrait($article['contenu'], 150)) ?>
                                        </p>
                                        <span class="text-primary small fw-bold">
                                            Lire la suite <i class="bi bi-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Recherche -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </h2>
                        <form action="articles.php" method="get">
                            <div class="input-group">
                                <input type="search" class="form-control" name="q"
                                       placeholder="Mots-clés..." value="<?= e($recherche) ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

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

                <!-- Articles récents -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-clock-history me-2"></i>Articles récents
                        </h2>
                        <ul class="list-unstyled">
                            <?php
                            $recents = getArticlesPublies(5);
                            foreach ($recents as $recent):
                            ?>
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="article.php?slug=<?= e($recent['slug']) ?>" class="text-decoration-none text-dark">
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
</section>

<?php require_once 'includes/footer.php'; ?>
