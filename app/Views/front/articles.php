<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../../../config/helpers.php';

$pageTitle = 'Tous les articles | Iran News';
$pageDescription = 'Retrouvez tous nos articles sur la situation en Iran : politique, militaire et diplomatie.';
$pageKeywords = 'Iran, articles Iran, actualités Iran';

require_once __DIR__ . '/../layouts/front_header.php';

$articles = getArticlesPublies();
?>

<div class="container py-5">
 <h1 class="h2 fw-bold mb-4 border-bottom pb-2">
 Tous les articles
 </h1>

 <?php if (empty($articles)): ?>
 <div class="alert alert-info">Aucun article publié pour le moment.</div>
 <?php else: ?>
 <div class="row">
 <?php foreach ($articles as $article): ?>
 <div class="col-md-6 mb-4">
 <article class="card h-100 border-0 shadow-sm">
 <?php if ($article['image_principale']): ?>
 <img src="<?= imageUrl($article['image_principale']) ?>"
 class="card-img-top"
 style="height:200px;object-fit:cover"
 alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
 <?php else: ?>
 <div class="bg-secondary d-flex align-items-center justify-content-center" style="height:200px">
 
 </div>
 <?php endif; ?>
 <div class="card-body d-flex flex-column">
 <span class="badge bg-primary mb-2 align-self-start"><?= e($article['categorie_nom']) ?></span>
 <h2 class="card-title h5">
 <a href="<?= articleUrl($article['id'], $article['slug']) ?>"
 class="text-decoration-none text-dark stretched-link">
 <?= e($article['titre']) ?>
 </a>
 </h2>
 <p class="card-text text-muted small flex-grow-1">
 <?= e(genererExtrait($article['contenu'], 120)) ?>
 </p>
 <small class="text-muted mt-2">
 <?= formaterDate($article['date_publication']) ?>
 </small>
 </div>
 </article>
 </div>
 <?php endforeach; ?>
 </div>
 <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/front_footer.php'; ?>