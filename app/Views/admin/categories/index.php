<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/config/slug.php';
require_once ROOT . '/app/Models/ArticleModel.php';

$model = new ArticleModel($pdo);
$erreur = '';
$success = '';

if (isset($_GET['delete'])) {
    try {
        $model->deleteCategory((int)$_GET['delete']);
        header('Location: ' . adminUrl('categories'));
        exit;
    } catch (RuntimeException $e) {
        $erreur = $e->getMessage();
    } catch (PDOException $e) {
        $erreur = 'Suppression impossible pour des raisons de cohérence des données.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $slug = generer_slug($nom);
    if ($nom) {
        try {
            $model->createCategory($nom, $slug);
            $success = "Catégorie créée avec succès !";
        } catch (PDOException $e) {
            $erreur = "Cette catégorie existe déjà.";
        }
    }
}

$categories = $model->getAllCategories();
?>

<?php require_once ROOT . '/app/Views/layouts/admin_header.php'; ?>

<!-- Page Header avec style moderne -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1">
            <i class="bi bi-folder me-2" aria-hidden="true"></i>
            Gestion des Catégories
        </h1>
        <p class="text-muted mb-0">
            <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
            <?= count($categories) ?> catégorie<?= count($categories) > 1 ? 's' : '' ?> disponible<?= count($categories) > 1 ? 's' : '' ?>
        </p>
    </div>
</div>

<!-- Messages de succès et d'erreur -->
<?php if ($erreur): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erreur !</strong> <?= htmlspecialchars($erreur) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Succès !</strong> <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>

<!-- Formulaire d'ajout -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
            Ajouter une nouvelle catégorie
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" class="row g-3 align-items-end">
            <div class="col-md-9">
                <label for="nom" class="form-label">
                    <i class="bi bi-folder-plus me-1" aria-hidden="true"></i>
                    Nom de la catégorie
                </label>
                <input
                    type="text"
                    name="nom"
                    id="nom"
                    class="form-control"
                    placeholder="Ex: Politique, Sport, Technologie..."
                    required
                    autocomplete="off">
                <div class="form-text">
                    <i class="bi bi-lightbulb me-1" aria-hidden="true"></i>
                    Le slug sera généré automatiquement
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des catégories -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-collection me-2" aria-hidden="true"></i>
            Catégories existantes (<?= count($categories) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <i class="bi bi-folder-x" aria-hidden="true"></i>
                <p>Aucune catégorie pour le moment</p>
                <p class="text-muted small">Ajoutez votre première catégorie ci-dessus</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 80px">#</th>
                            <th scope="col" style="width: 35%">
                                <i class="bi bi-folder me-1" aria-hidden="true"></i>
                                Nom
                            </th>
                            <th scope="col" style="width: 40%">
                                <i class="bi bi-link-45deg me-1" aria-hidden="true"></i>
                                Slug
                            </th>
                            <th scope="col" style="width: 20%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): ?>
                        <tr>
                            <td class="fw-semibold text-muted"><?= $c['id'] ?></td>
                            <td class="fw-medium">
                                <i class="bi bi-folder text-primary me-2" aria-hidden="true"></i>
                                <?= htmlspecialchars($c['nom']) ?>
                            </td>
                            <td>
                                <code><?= htmlspecialchars($c['slug']) ?></code>
                            </td>
                            <td class="text-center">
                                <a href="<?= adminUrl('categories', ['delete' => $c['id']]) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('⚠️ Voulez-vous vraiment supprimer la catégorie « <?= htmlspecialchars($c['nom']) ?> » ?\n\nAttention : Cette action est irréversible !')"
                                   title="Supprimer la catégorie">
                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT . '/app/Views/layouts/admin_footer.php'; ?>