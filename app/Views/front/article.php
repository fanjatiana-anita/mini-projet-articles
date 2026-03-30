<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../../../config/helpers.php';

// Récupérer l'ID et le slug de l'URL (format: /article/5-guerre-iran)
$id = intval($_GET['id'] ?? 0);
$slug = trim($_GET['slug'] ?? '');

// Vérification basique
if ($id <= 0 || empty($slug)) { 
    header('Location: ' . BASE_URL . '/articles'); 
    exit; 
}

// Recherche rapide par ID
$article = getArticleById($id);

// Si article non trouvé OU slug ne correspond pas → 404 avec redirection
if (!$article) {
    http_response_code(404);
    $pageTitle = 'Article non trouvé | Iran News';
    $pageDescription = 'L\'article que vous recherchez n\'existe pas.';
    require_once __DIR__ . '/../layouts/front_header.php';
    ?>
    <section class="py-5">
    <div class="container text-center">
    
    <h1 class="h2 mt-4">Article non trouvé</h1>
    <p class="text-muted">L'article que vous recherchez n\'existe pas ou a été supprimé.</p>
    <a href="<?= BASE_URL ?>/articles" class="btn btn-primary">
    Retour aux articles
    </a>
    </div>
    </section>
    <?php
    require_once __DIR__ . '/../layouts/front_footer.php';
    exit;
}

// Valider le slug pour SEO (si le slug ne match pas, rediriger vers la bonne URL)
if (slugify($article['slug']) !== slugify($slug)) {
    header('Location: ' . articleUrl($article['id'], $article['slug']), true, 301);
    exit;
}

$pageTitle = e($article['titre']) . ' | Iran News';
$pageDescription = $article['meta_description'] ?? genererExtrait($article['contenu'], 160);
$pageKeywords = 'Iran, ' . $article['categorie_nom'] . ', actualités Iran';

require_once __DIR__ . '/../layouts/front_header.php';

$articlesSimilaires = array_slice(
 array_filter(
 getArticlesByCategorie($article['categorie_slug']),
 fn($a) => $a['id'] !== $article['id']
 ), 0, 3
);
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
 <div class="container">
 <ol class="breadcrumb mb-0">
 <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
 <li class="breadcrumb-item">
 <a href="<?= BASE_URL ?>/articles">Articles</a>
 </li>
 <li class="breadcrumb-item">
 <a href="<?= BASE_URL ?>/categorie/<?= e($article['categorie_slug']) ?>">
 <?= e($article['categorie_nom']) ?>
 </a>
 </li>
 <li class="breadcrumb-item active"><?= e($article['titre']) ?></li>
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
 <!-- h1 = titre de l'article — UN SEUL par page (SEO) -->
 <h1 class="display-5 fw-bold mb-3"><?= e($article['titre']) ?></h1>
 <div class="d-flex align-items-center text-muted mb-4">
 <span class="me-4">
 
 <?= formaterDate($article['date_publication']) ?>
 </span>
 <span>
 
 <a href="<?= BASE_URL ?>/categorie/<?= e($article['categorie_slug']) ?>"
 class="text-muted text-decoration-none">
 <?= e($article['categorie_nom']) ?>
 </a>
 </span>
 </div>
 </header>

 <?php if ($article['image_principale']): ?>
 <figure class="mb-4">
 <!-- alt rempli depuis la BDD — obligatoire pour Lighthouse -->
 <img src="<?= imageUrl($article['image_principale']) ?>"
 class="img-fluid rounded shadow-sm w-100"
 style="max-height:400px;object-fit:cover"
 alt="<?= e($article['alt_image'] ?? $article['titre']) ?>">
 <?php if ($article['alt_image']): ?>
 <figcaption class="text-muted small mt-2 text-center">
 <?= e($article['alt_image']) ?>
 </figcaption>
 <?php endif; ?>
 </figure>
 <?php endif; ?>

 <!-- Contenu HTML généré par TinyMCE -->
 <div class="article-content">
 <?= $article['contenu'] ?>
 </div>

 <div class="border-top border-bottom py-4 my-4">
 <h2 class="h6 fw-bold mb-3">Partager cet article</h2>
 <div class="d-flex gap-2">
 <a href="#" class="btn btn-outline-primary btn-sm" title="Facebook"></a>
 <a href="#" class="btn btn-outline-info btn-sm" title="Twitter"></a>
 <a href="#" class="btn btn-outline-success btn-sm" title="WhatsApp"></a>
 <a href="#" class="btn btn-outline-primary btn-sm" title="LinkedIn"></a>
 </div>
 </div>

 <nav class="d-flex justify-content-between py-3">
 <a href="<?= BASE_URL ?>/articles" class="btn btn-outline-secondary">
 Tous les articles
 </a>
 <a href="<?= BASE_URL ?>/categorie/<?= e($article['categorie_slug']) ?>"
 class="btn btn-outline-primary">
 Plus de <?= e($article['categorie_nom']) ?>
 </a>
 </nav>
 </div>

 <!-- Sidebar -->
 <div class="col-lg-4">
 <?php if (!empty($articlesSimilaires)): ?>
 <div class="card border-0 shadow-sm mb-4">
 <div class="card-body">
 <h2 class="h5 fw-bold mb-3">Articles similaires</h2>
 <?php foreach ($articlesSimilaires as $s): ?>
 <div class="mb-3 pb-3 border-bottom">
 <h3 class="h6 mb-1">
 <a href="<?= articleUrl($s['id'], $s['slug']) ?>"
 class="text-decoration-none text-dark">
 <?= e($s['titre']) ?>
 </a>
 </h3>
 <small class="text-muted"><?= formaterDate($s['date_publication']) ?></small>
 </div>
 <?php endforeach; ?>
 </div>
 </div>
 <?php endif; ?>

 <div class="card border-0 shadow-sm mb-4">
 <div class="card-body">
 <h2 class="h5 fw-bold mb-3">Catégories</h2>
 <ul class="list-group list-group-flush">
 <?php foreach (getCategories() as $cat): ?>
 <li class="list-group-item d-flex justify-content-between align-items-center px-0">
 <a href="<?= BASE_URL ?>/categorie/<?= e($cat['slug']) ?>"
 class="text-decoration-none">
 <?= e($cat['nom']) ?>
 </a>
 <span class="badge bg-primary rounded-pill">
 <?= count(getArticlesByCategorie($cat['slug'])) ?>
 </span>
 </li>
 <?php endforeach; ?>
 </ul>
 </div>
 </div>

 <div class="card border-0 shadow-sm">
 <div class="card-body">
 <h2 class="h5 fw-bold mb-3">Derniers articles</h2>
 <ul class="list-unstyled">
 <?php foreach (getArticlesPublies(5) as $r): ?>
 <?php if ($r['id'] !== $article['id']): ?>
 <li class="mb-3 pb-3 border-bottom">
 <a href="<?= articleUrl($r['id'], $r['slug']) ?>"
 class="text-decoration-none text-dark">
 <small class="d-block text-muted"><?= formaterDate($r['date_publication']) ?></small>
 <?= e($r['titre']) ?>
 </a>
 </li>
 <?php endif; ?>
 <?php endforeach; ?>
 </ul>
 </div>
 </div>
 </div>
 </div>
 </div>
</article>

<?php require_once __DIR__ . '/../layouts/front_footer.php'; ?>