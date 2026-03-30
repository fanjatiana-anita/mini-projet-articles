<?php
require_once __DIR__ . '/../includes/functions.php';
http_response_code(404);
$pageTitle = 'Page non trouvée | Iran News';
$pageDescription = 'La page que vous recherchez n\'existe pas.';
require_once __DIR__ . '/../layouts/front_header.php';
?>
<section class="py-5">
 <div class="container text-center">
 
 <h1 class="display-4 fw-bold mt-4">404</h1>
 <p class="lead text-muted">Page non trouvée</p>
 <a href="/mini-projet-articles/index.php" class="btn btn-primary mt-3">
 Retour à l'accueil
 </a>
 </div>
</section>
<?php require_once __DIR__ . '/../layouts/front_footer.php'; ?>