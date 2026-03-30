<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/Models/DashboardModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

$dashboard = new DashboardModel($pdo);
$stats = $dashboard->getStats();
$derniers = $dashboard->getLast5();
?>

<h1 class="h3 mb-4">Tableau de bord</h1>

<div class="row g-3 mb-4">
 <div class="col-md-4">
 <div class="card text-white bg-primary text-center py-3">
 <div class="card-body">
 <h2 class="display-5 fw-bold"><?= $stats['nb_articles'] ?></h2>
 <p class="mb-2">Articles au total</p>
 <a href="<?= adminUrl('articles') ?>" class="btn btn-light btn-sm">Gérer</a>
 </div>
 </div>
 </div>
 <div class="col-md-4">
 <div class="card text-white bg-success text-center py-3">
 <div class="card-body">
 <h2 class="display-5 fw-bold"><?= $stats['nb_publies'] ?></h2>
 <p class="mb-2">Articles publiés</p>
 <a href="<?= BASE_URL ?>/" class="btn btn-light btn-sm" target="_blank">Voir le site</a>
 </div>
 </div>
 </div>
 <div class="col-md-4">
 <div class="card text-white bg-secondary text-center py-3">
 <div class="card-body">
 <h2 class="display-5 fw-bold"><?= $stats['nb_categories'] ?></h2>
 <p class="mb-2">Catégories</p>
 <a href="<?= adminUrl('categories') ?>" class="btn btn-light btn-sm">Gérer</a>
 </div>
 </div>
 </div>
</div>

<div class="card">
 <div class="card-header fw-bold">5 derniers articles</div>
 <table class="table table-striped mb-0">
 <thead class="table-light">
 <tr><th>Titre</th><th>Statut</th><th>Date</th></tr>
 </thead>
 <tbody>
 <?php foreach ($derniers as $a): ?>
 <tr>
 <td><?= htmlspecialchars($a['titre']) ?></td>
 <td><span class="badge <?= $a['statut']==='publie'?'bg-success':'bg-secondary' ?>"><?= $a['statut'] ?></span></td>
 <td><?= $a['date_publication'] ?></td>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>