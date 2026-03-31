<?php
/**
 * Layout FrontOffice — Footer
 * $navCategories est déjà chargé par front_header.php
 */
?>
</main>

<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row">

            <div class="col-md-4 mb-4">
                <h2 class="h5 fw-bold mb-3">
                    <i class="bi bi-newspaper me-2"></i>Iran News
                </h2>
                <p class="text-muted">
                    Votre source d'information fiable sur la situation en Iran.
                    Analyses politiques, militaires et diplomatiques.
                </p>
            </div>

            <div class="col-md-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Catégories</h2>
                <ul class="list-unstyled">
                    <?php foreach ($navCategories as $cat): ?>
                        <li class="mb-2">
                            <a href="<?= BASE_URL ?>/categorie/<?= e($cat['slug']) ?>"
                               class="text-muted text-decoration-none">
                                <i class="bi bi-chevron-right me-1"></i><?= e($cat['nom']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Liens utiles</h2>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>/" class="text-muted text-decoration-none">
                            <i class="bi bi-house me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>/articles" class="text-muted text-decoration-none">
                            <i class="bi bi-file-text me-1"></i>Tous les articles
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary">

        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-muted mb-0">&copy; <?= date('Y') ?> Iran News. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="text-muted mb-0">Mini-projet Web Design – L3 2026</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
