<?php
require_once __DIR__ . '/../includes/functions.php';
// Charger les helpers (BASE_URL, helpers d'assets)
require_once __DIR__ . '/../../../config/helpers.php';

$pageTitle = 'Iran News - Actualités et analyses sur la situation en Iran';
$pageDescription = 'Suivez toute l\'actualité sur la situation en Iran : politique, militaire et diplomatie. Articles de fond et analyses sur le conflit iranien.';
$pageKeywords = 'Iran, actualités Iran, guerre Iran, conflit Iran, politique iranienne, sanctions Iran, nucléaire Iran';

require_once __DIR__ . '/../layouts/front_header.php';

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
 <a href="<?= BASE_URL ?>/articles" class="btn btn-light btn-lg">Voir tous les articles</a>
 </div>
 <div class="col-lg-4 d-none d-lg-block text-center"></div>
 </div>
 </div>
</section>

<!-- Derniers articles -->
<section class="py-5">
 <div class="container">
 <h2 class="h3 fw-bold mb-4 border-bottom pb-2">Derniers articles</h2>

 <?php if (empty($derniersArticles)): ?>
 <div class="alert alert-info">Aucun article publié pour le moment.</div>
 <?php else: ?>
 <div class="row">
 <?php foreach ($derniersArticles as $index => $article): ?>
 <?php if ($index === 0): ?>
 <!-- Article principal -->
 <div class="col-lg-8 mb-4">
 <article class="card h-100 border-0 shadow-sm">
 <?php if ($article['image_principale']): ?>
 <img src="<?= imageUrl($article['image_principale']) ?>"
 class="card-img-top"
 style="height:320px;object-fit:cover"
 alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
 <?php else: ?>
 <div class="bg-secondary d-flex align-items-center justify-content-center" style="height:320px">
 <div class="text-center p-5 bg-light">Pas d'image</div>
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
 <p class="card-text text-muted"><?= e(genererExtrait($article['contenu'], 200)) ?></p>
 <small class="text-muted">
 <?= formaterDate($article['date_publication']) ?>
 </small>
 </div>
 </article>
 </div>
 <div class="col-lg-4">
 <?php else: ?>
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
 <?= formaterDate($article['date_publication']) ?>
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
 <h2 class="h3 fw-bold mb-4 border-bottom pb-2">Explorer par catégorie</h2>
 <div class="row">
 <?php foreach ($categories as $categorie): ?>
 <div class="col-md-4 mb-4">
 <a href="<?= BASE_URL ?>/categorie/<?= e($categorie['slug']) ?>"
 class="text-decoration-none">
 <div class="card border-0 shadow-sm h-100">
 <div class="card-body text-center py-4">
 <h3 class="card-title h5 text-dark"><?= e($categorie['nom']) ?></h3>
 <p class="card-text text-muted small">Découvrir les articles</p>
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
 Iran News est une plateforme d'information dédiée à l'analyse de la situation en Iran.
 Notre équipe suit de près les développements politiques, militaires et diplomatiques.
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

<?php require_once __DIR__ . '/../layouts/front_footer.php'; ?>