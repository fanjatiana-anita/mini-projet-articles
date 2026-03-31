<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/Models/DashboardModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

$dashboard = new DashboardModel($pdo);
$stats = $dashboard->getStats();
$derniers = $dashboard->getLast5();
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1">Tableau de bord</h1>
        <p class="text-muted mb-0">Bienvenue <?= htmlspecialchars($_SESSION['username']) ?> 👋</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= adminUrl('articles&action=add') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
            Nouvel article
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card stat-card-primary">
            <div class="card-body">
                <div class="stat-label">Articles au total</div>
                <div class="stat-number"><?= $stats['nb_articles'] ?></div>
                <a href="<?= adminUrl('articles') ?>" class="btn btn-light btn-sm mt-2">
                    <i class="bi bi-arrow-right me-1" aria-hidden="true"></i>
                    Gérer les articles
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card stat-card-success">
            <div class="card-body">
                <div class="stat-label">Articles publiés</div>
                <div class="stat-number"><?= $stats['nb_publies'] ?></div>
                <a href="<?= BASE_URL ?>/" class="btn btn-light btn-sm mt-2" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-eye me-1" aria-hidden="true"></i>
                    Voir le site
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card stat-card-secondary">
            <div class="card-body">
                <div class="stat-label">Catégories</div>
                <div class="stat-number"><?= $stats['nb_categories'] ?></div>
                <a href="<?= adminUrl('categories') ?>" class="btn btn-light btn-sm mt-2">
                    <i class="bi bi-folder me-1" aria-hidden="true"></i>
                    Gérer les catégories
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Articles Table -->
<div class="admin-card card">
    <div class="admin-card-header card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2" aria-hidden="true"></i>5 derniers articles</span>
        <a href="<?= adminUrl('articles') ?>" class="btn btn-sm btn-outline-primary">
            Voir tous <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table table table-hover mb-0" role="table">
            <thead>
                <tr>
                    <th scope="col" style="width: 50%">Titre</th>
                    <th scope="col" style="width: 20%">Statut</th>
                    <th scope="col" style="width: 30%">Date de publication</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($derniers)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;" aria-hidden="true"></i>
                            Aucun article pour le moment
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($derniers as $a): ?>
                    <tr>
                        <td>
                            <a href="<?= adminUrl('articles&action=edit&id=' . $a['id']) ?>" class="text-decoration-none text-dark fw-medium">
                                <?= htmlspecialchars($a['titre']) ?>
                            </a>
                        </td>
                        <td>
                            <span class="admin-badge <?= $a['statut'] === 'publie' ? 'admin-badge-success' : 'admin-badge-secondary' ?>">
                                <?= $a['statut'] === 'publie' ? 'Publié' : ucfirst($a['statut']) ?>
                            </span>
                        </td>
                        <td class="text-muted">
                            <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                            <?= date('d/m/Y', strtotime($a['date_publication'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>