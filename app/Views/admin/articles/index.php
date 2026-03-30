<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/Models/ArticleModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

$model = new ArticleModel($pdo);
$articles = $model->getAll();
$deleted = $model->getDeleted();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Articles</h1>
    <a href="<?= adminUrl('articles', ['action' => 'create']) ?>" class="btn btn-primary">+ Nouvel article</a>
</div>

<!-- ARTICLES ACTIFS -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Articles actifs (<?= count($articles) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($articles)): ?>
            <div class="alert alert-info m-3 mb-0">Aucun article trouvé.</div>
        <?php else: ?>
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $a): ?>
                    <tr>
                        <td><?= $a['id'] ?></td>
                        <td><?= htmlspecialchars($a['titre']) ?></td>
                        <td><?= htmlspecialchars($a['categorie']) ?></td>
                        <td><span class="badge <?= $a['statut']==='publie'?'bg-success':'bg-secondary' ?>"><?= $a['statut'] ?></span></td>
                        <td><?= $a['date_publication'] ?></td>
                        <td class="d-flex gap-1">
                            <a href="<?= adminUrl('articles', ['action' => 'edit', 'id' => $a['id']]) ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <a href="<?= adminUrl('articles', ['action' => 'delete', 'id' => $a['id']]) ?>" class="btn btn-sm btn-danger">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- ARTICLES SUPPRIMÉS -->
<?php if (!empty($deleted)): ?>
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Articles supprimés (<?= count($deleted) ?>) - Récupération possible</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deleted as $a): ?>
                <tr class="table-danger opacity-75">
                    <td><?= $a['id'] ?></td>
                    <td><strike><?= htmlspecialchars($a['titre']) ?></strike></td>
                    <td><?= htmlspecialchars($a['categorie']) ?></td>
                    <td><span class="badge bg-danger"><?= $a['statut'] ?></span></td>
                    <td><?= $a['date_publication'] ?></td>
                    <td>
                        <!-- Bouton de récupération (à implémenter) -->
                        <small class="text-muted">Récupération non implémentée</small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once ROOT . '/app/Views/layouts/admin_footer.php'; ?>