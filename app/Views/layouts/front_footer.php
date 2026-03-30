</main>
<footer class="bg-dark text-light py-5 mt-5">
 <div class="container">
 <div class="row">
 <div class="col-md-4 mb-4">
 <h2 class="h5 fw-bold">Iran News</h2>
 <p class="text-muted">Votre source d'information fiable sur la situation en Iran.</p>
 </div>
 <div class="col-md-4 mb-4">
 <h2 class="h5 fw-bold">Catégories</h2>
 <ul class="list-unstyled">
 <?php
 $footerCats = getCategories();
 foreach ($footerCats as $cat):
 ?>
 <li class="mb-1">
 <a href="<?= BASE_URL ?>/categorie/<?= e($cat['slug']) ?>"
 class="text-muted text-decoration-none">
 <?= e($cat['nom']) ?>
 </a>
 </li>
 <?php endforeach; ?>
 </ul>
 </div>
 <div class="col-md-4 mb-4">
 <h2 class="h5 fw-bold">Liens utiles</h2>
 <ul class="list-unstyled">
 <li class="mb-1"><a href="<?= BASE_URL ?>/" class="text-muted text-decoration-none">Accueil</a></li>
 <li class="mb-1"><a href="<?= BASE_URL ?>/articles" class="text-muted text-decoration-none">Articles</a></li>
 </ul>
 </div>
 <div class="col-md-4 mb-4">
 <h2 class="h5 fw-bold">Administration</h2>
 <p class="text-muted text-sm">Accès à l'interface de gestion du site.</p>
 <a href="<?= BASE_URL_ADMIN ?>/login" class="btn btn-sm btn-outline-light">BackOffice</a>
 </div>
 </div>
 <hr class="border-secondary">
 <div class="row">
 <div class="col-md-6 text-center text-md-start">
 <p class="text-muted mb-0">&copy; <?= date('Y') ?> Iran News. Tous droits réservés.</p>
 </div>
 <div class="col-md-6 text-center text-md-end">
 <p class="text-muted mb-0">Projet Mini Web Design — ITU 2026</p>
 </div>
 </div>
 </div>
</footer>

<!-- Modal: Admin Login Hint -->
<div class="modal fade" id="adminModal" tabindex="-1">
 <div class="modal-dialog modal-sm">
 <div class="modal-content">
 <div class="modal-header bg-primary text-white">
 <h5 class="modal-title">Admin: Credentials Test</h5>
 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body">
 <div class="bg-light p-2 rounded mb-2">
 <small><strong>login:</strong> admin<br><strong>password:</strong> admin123</small>
 </div>
 </div>
 <div class="modal-footer">
 <form method="POST" action="<?= BASE_URL_ADMIN ?>/login" class="w-100">
 <input type="hidden" name="login" value="admin">
 <input type="hidden" name="password" value="admin123">
 <button type="submit" class="btn btn-primary w-100">Se connecter</button>
 </form>
 </div>
 </div>
 </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>