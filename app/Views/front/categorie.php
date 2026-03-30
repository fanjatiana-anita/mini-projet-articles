<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../../../config/helpers.php';

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) { header('Location: ' . BASE_URL . '/articles'); exit; }

$categorie = getCategorieBySlug($slug);

if (!$categorie) {
 http_response_code(404);
 $pageTitle = 'Catégorie non trouvée | Iran News';
 $pageDescription = 'La catégorie que vous recherchez n\'existe pas.';
 require_once __DIR__ . '/../layouts/front_header.php';
 ?>
 <section class="py-5">
 <div class="container text-center">
 
 <h1 class="h2 mt-4">Catégorie non trouvée</h1>
 <a href="<?= BASE_URL ?>/articles" class="btn btn-primary">
 Voir tous les articles
 </a>
 </div>
 </section>
 <?php
 require_once __DIR__ . '/../layouts/front_footer.php';
 exit;
}

$articles = getArticlesByCategorie($slug);
$pageTitle = e($categorie['nom']) . ' - Articles | Iran News';
$pageDescription = 'Découvrez tous nos articles sur ' . $categorie['nom'] . '. Analyses et actualités sur l\'Iran.';
$pageKeywords = 'Iran, ' . $categorie['nom'] . ', actualités Iran';

require_once __DIR__ . '/../layouts/front_header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
 <div class="container">
 <ol class="breadcrumb mb-0">
 <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
 <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/articles">Articles</a></li>
 <li class="breadcrumb-item active"><?= e($categorie['nom']) ?></li>
 </ol>
 </div>
</nav>

<!-- En-tête catégorie -->
<section class="bg-primary text-white py-4">
 <div class="container">
 <!-- h1 unique par page -->
 <h1 class="display-6 fw-bold mb-2"><?= e($categorie['nom']) ?></h1>
 <p class="lead mb-0">
 <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> dans cette catégorie
 </p>
 </div>
</section>

<section class="py-5">
 <div class="container">
 <div class="row">
 <!-- Articles -->
 <div class="col-lg-8">
 <?php if (empty($articles)): ?>
 <div class="alert alert-info">Aucun article dans cette catégorie.</div>
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
 <div class="bg-secondary d-flex align-items-center justify-content-center" style="height:180px">
 
 </div>
 <?php endif; ?>
 <div class="card-body d-flex flex-column">
 <h2 class="card-title h5">
 <a href="<?= articleUrl($article['id'], $article['slug']) ?>"
 class="text-decoration-none text-dark stretched-link">
 <?= e($article['titre']) ?>
 </a>
 </h2>
 <p class="card-text text-muted small">
 <?= e(genererExtrait($article['contenu'], 100)) ?>
 </p>
 <small class="text-muted mt-auto">
 <?= formaterDate($article['date_publication']) ?>
 </small>
 </div>
 </article>
 </div>
 <?php endforeach; ?>
 </div>
 <?php endif; ?>
 </div>

 <!-- Sidebar -->
 <div class="col-lg-4">
 <div class="card border-0 shadow-sm mb-4">
 <div class="card-body">
 <h2 class="h5 fw-bold mb-3">Autres catégories</h2>
 <ul class="list-group list-group-flush">
 <?php foreach (getCategories() as $cat): ?>
 <?php if ($cat['slug'] !== $slug): ?>
 <li class="list-group-item d-flex justify-content-between align-items-center px-0">
 <a href="<?= BASE_URL ?>/categorie/<?= e($cat['slug']) ?>"
 class="text-decoration-none">
 <?= e($cat['nom']) ?>
 </a>
 <span class="badge bg-primary rounded-pill">
 <?= count(getArticlesByCategorie($cat['slug'])) ?>
 </span>
 </li>
 <?php endif; ?>
 <?php endforeach; ?>
 </ul>
 </div>
 </div>

 <div class="card border-0 shadow-sm">
 <div class="card-body">
 <h2 class="h5 fw-bold mb-3">Derniers articles</h2>
 <ul class="list-unstyled">
 <?php foreach (getArticlesPublies(5) as $r): ?>
 <li class="mb-3 pb-3 border-bottom">
 <a href="<?= articleUrl($r['id'], $r['slug']) ?>"
 class="text-decoration-none text-dark">
 <span class="badge bg-secondary me-1"><?= e($r['categorie_nom']) ?></span>
 <small class="d-block text-muted mt-1"><?= formaterDate($r['date_publication']) ?></small>
 <?= e($r['titre']) ?>
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

<?php require_once __DIR__ . '/../layouts/front_footer.php'; ?>