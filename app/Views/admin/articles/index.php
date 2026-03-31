<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/Models/ArticleModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

$model = new ArticleModel($pdo);
$articles = $model->getAll();
$deleted = $model->getDeleted();
?>

<!-- Page Header avec style moderne -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1">
            <i class="bi bi-file-earmark-text me-2" aria-hidden="true"></i>
            Gestion des Articles
        </h1>
        <p class="text-muted mb-0">
            <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
            <?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?> actif<?= count($articles) > 1 ? 's' : '' ?>
            <?php if (!empty($deleted)): ?>
                • <?= count($deleted) ?> supprimé<?= count($deleted) > 1 ? 's' : '' ?>
            <?php endif; ?>
        </p>
    </div>
    <div>
        <a href="<?= adminUrl('articles', ['action' => 'create']) ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
            Nouvel article
        </a>
    </div>
</div>

<!-- ARTICLES ACTIFS -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-collection me-2" aria-hidden="true"></i>
            Articles actifs (<?= count($articles) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($articles)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox" aria-hidden="true"></i>
                <p>Aucun article trouvé</p>
                <a href="<?= adminUrl('articles', ['action' => 'create']) ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                    Créer votre premier article
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 60px">#</th>
                            <th scope="col" style="width: 35%">Titre</th>
                            <th scope="col" style="width: 15%">Catégorie</th>
                            <th scope="col" style="width: 12%">Statut</th>
                            <th scope="col" style="width: 15%">Date</th>
                            <th scope="col" style="width: 18%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $a): ?>
                        <tr>
                            <td class="fw-semibold text-muted"><?= $a['id'] ?></td>
                            <td class="fw-medium">
                                <i class="bi bi-file-earmark-text text-primary me-2" aria-hidden="true"></i>
                                <?= htmlspecialchars($a['titre']) ?>
                            </td>
                            <td>
                                <i class="bi bi-folder text-secondary me-1" aria-hidden="true"></i>
                                <?= htmlspecialchars($a['categorie']) ?>
                            </td>
                            <td>
                                <?php if ($a['statut'] === 'publie'): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                                        Publié
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-clock" aria-hidden="true"></i>
                                        <?= ucfirst($a['statut']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted">
                                <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                                <?= date('d/m/Y', strtotime($a['date_publication'])) ?>
                            </td>
                            <td>
                                <div class="action-buttons justify-content-center">
                                    <a href="<?= adminUrl('articles', ['action' => 'edit', 'id' => $a['id']]) ?>"
                                       class="btn btn-sm btn-warning"
                                       title="Modifier l'article">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                        Modifier
                                    </a>
                                    <a href="<?= adminUrl('articles', ['action' => 'delete', 'id' => $a['id']]) ?>"
                                       class="btn btn-sm btn-danger"
                                       title="Supprimer l'article">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                        Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ARTICLES SUPPRIMÉS -->
<?php if (!empty($deleted)): ?>
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">
            <i class="bi bi-trash me-2" aria-hidden="true"></i>
            Articles supprimés (<?= count($deleted) ?>)
            <small class="ms-2" style="opacity: 0.9; font-size: 0.875rem;">Récupération possible</small>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 60px">#</th>
                        <th scope="col" style="width: 35%">Titre</th>
                        <th scope="col" style="width: 15%">Catégorie</th>
                        <th scope="col" style="width: 12%">Statut</th>
                        <th scope="col" style="width: 15%">Date</th>
                        <th scope="col" style="width: 18%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deleted as $a): ?>
                    <tr class="table-danger">
                        <td class="fw-semibold text-muted"><?= $a['id'] ?></td>
                        <td class="fw-medium">
                            <i class="bi bi-file-earmark-x text-danger me-2" aria-hidden="true"></i>
                            <del><?= htmlspecialchars($a['titre']) ?></del>
                        </td>
                        <td>
                            <i class="bi bi-folder text-secondary me-1" aria-hidden="true"></i>
                            <?= htmlspecialchars($a['categorie']) ?>
                        </td>
                        <td>
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle" aria-hidden="true"></i>
                                Supprimé
                            </span>
                        </td>
                        <td class="text-muted">
                            <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                            <?= date('d/m/Y', strtotime($a['date_publication'])) ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">
                                <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                                Récupération non implémentée
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once ROOT . '/app/Views/layouts/admin_footer.php'; ?>