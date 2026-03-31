<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/Models/DashboardModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

$dashboard = new DashboardModel($pdo);
$stats = $dashboard->getStats();
$derniers = $dashboard->getLast5();

// Calculer le pourcentage d'articles publiés
$pctPublies = $stats['nb_articles'] > 0
    ? round(($stats['nb_publies'] / $stats['nb_articles']) * 100)
    : 0;
?>



<!-- Stats Cards -->
<div class="dashboard-stats">
    <div class="dashboard-stat-card">
        <div class="dashboard-stat-icon primary">
            <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
        </div>
        <div class="dashboard-stat-content">
            <div class="stat-value"><?= $stats['nb_articles'] ?></div>
            <p class="stat-label">Articles au total</p>
            <a href="<?= adminUrl('articles') ?>" class="stat-link">
                Voir tous <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <div class="dashboard-stat-card">
        <div class="dashboard-stat-icon success">
            <i class="bi bi-check-circle" aria-hidden="true"></i>
        </div>
        <div class="dashboard-stat-content">
            <div class="stat-value"><?= $stats['nb_publies'] ?></div>
            <p class="stat-label">Articles publiés (<?= $pctPublies ?>%)</p>
            <a href="<?= BASE_URL ?>/" class="stat-link" target="_blank" rel="noopener noreferrer">
                Voir le site <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <div class="dashboard-stat-card">
        <div class="dashboard-stat-icon secondary">
            <i class="bi bi-folder" aria-hidden="true"></i>
        </div>
        <div class="dashboard-stat-content">
            <div class="stat-value"><?= $stats['nb_categories'] ?></div>
            <p class="stat-label">Catégories</p>
            <a href="<?= adminUrl('categories') ?>" class="stat-link">
                Gérer <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="<?= adminUrl('articles', ['action' => 'create']) ?>" class="quick-action-btn">
        <i class="bi bi-plus-circle" aria-hidden="true"></i>
        <span>Nouvel article</span>
    </a>
    <a href="<?= adminUrl('categories') ?>" class="quick-action-btn">
        <i class="bi bi-folder-plus" aria-hidden="true"></i>
        <span>Nouvelle catégorie</span>
    </a>
    <a href="<?= adminUrl('articles') ?>" class="quick-action-btn">
        <i class="bi bi-pencil-square" aria-hidden="true"></i>
        <span>Modifier articles</span>
    </a>
    <a href="<?= BASE_URL ?>/" class="quick-action-btn" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-eye" aria-hidden="true"></i>
        <span>Voir le site</span>
    </a>
</div>

<!-- Recent Articles -->
<div class="dashboard-section">
    <div class="dashboard-section-header">
        <h2>
            <i class="bi bi-clock-history" aria-hidden="true"></i>
            Derniers articles
        </h2>
        <a href="<?= adminUrl('articles') ?>" class="btn btn-sm btn-outline-primary">
            Voir tous <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </a>
    </div>

    <?php if (empty($derniers)): ?>
        <div class="dashboard-empty">
            <i class="bi bi-inbox d-block" aria-hidden="true"></i>
            <p>Aucun article pour le moment</p>
            <a href="<?= adminUrl('articles', ['action' => 'create']) ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                Créer votre premier article
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($derniers as $a): ?>
        <div class="dashboard-article-item">
            <div class="dashboard-article-icon">
                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
            </div>
            <div class="dashboard-article-info">
                <div class="dashboard-article-title">
                    <a href="<?= adminUrl('articles', ['action' => 'edit', 'id' => $a['id']]) ?>">
                        <?= htmlspecialchars($a['titre']) ?>
                    </a>
                </div>
                <div class="dashboard-article-meta">
                    <span>
                        <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                        <?= date('d/m/Y', strtotime($a['date_publication'])) ?>
                    </span>
                </div>
            </div>
            <div class="dashboard-article-status">
                <?php if ($a['statut'] === 'publie'): ?>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                        Publié
                    </span>
                <?php else: ?>
                    <span class="badge bg-secondary">
                        <i class="bi bi-clock me-1" aria-hidden="true"></i>
                        <?= ucfirst($a['statut']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
