 </main>

 <!-- Footer -->
 <footer class="bg-dark text-light py-5 mt-5">
 <div class="container">
 <div class="row">
 <!-- À propos -->
 <div class="col-md-4 mb-4">
 <h2 class="h5 fw-bold mb-3">
 Iran News
 </h2>
 <p class="text-muted">
 Votre source d'information fiable sur la situation en Iran.
 Analyses politiques, militaires et diplomatiques.
 </p>
 </div>

 <!-- Catégories -->
 <div class="col-md-4 mb-4">
 <h2 class="h5 fw-bold mb-3">Catégories</h2>
 <ul class="list-unstyled">
 <?php
 $footerCategories = getCategories();
 foreach ($footerCategories as $cat):
 ?>
 <li class="mb-2">
 <a href="categorie.php?slug=<?= e($cat['slug']) ?>" class="text-muted text-decoration-none">
 <?= e($cat['nom']) ?>
 </a>
 </li>
 <?php endforeach; ?>
 </ul>
 </div>

 <!-- Liens utiles -->
 <div class="col-md-4 mb-4">
 <h2 class="h5 fw-bold mb-3">Liens utiles</h2>
 <ul class="list-unstyled">
 <li class="mb-2">
 <a href="index.php" class="text-muted text-decoration-none">
 Accueil
 </a>
 </li>
 <li class="mb-2">
 <a href="articles.php" class="text-muted text-decoration-none">
 Tous les articles
 </a>
 </li>
 <li class="mb-2">
 <a href="#" class="text-muted text-decoration-none">
 Contact
 </a>
 </li>
 <li class="mb-2">
 <a href="#" class="text-muted text-decoration-none">
 À propos
 </a>
 </li>
 </ul>
 </div>
 </div>

 <hr class="border-secondary">

 <!-- Copyright -->
 <div class="row">
 <div class="col-md-6 text-center text-md-start">
 <p class="text-muted mb-0">
 &copy; <?= date('Y') ?> Iran News. Tous droits réservés.
 </p>
 </div>
 <div class="col-md-6 text-center text-md-end">
 <p class="text-muted mb-0">
 Projet Mini Web Design - ITU 2026
 </p>
 </div>
 </div>
 </div>
 </footer>

 <!-- Bootstrap 5 JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
