<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/config/slug.php';
require_once ROOT . '/app/Models/ArticleModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

$model = new ArticleModel($pdo);
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $nom = trim($_POST['nom'] ?? '');
 $slug = generer_slug($nom);
 if ($nom) {
 try {
 $model->createCategory($nom, $slug);
 } catch (PDOException $e) {
 $erreur = "Catégorie déjà existante.";
 }
 }
 header('Location: ' . adminUrl('categories'));
 exit;
}

if (isset($_GET['delete'])) {
 $model->deleteCategory(intval($_GET['delete']));
 header('Location: ' . adminUrl('categories'));
 exit;
}

$categories = $model->getAllCategories();
?>

<h1 class="h3 mb-4">Catégories</h1>

<?php if ($erreur): ?>
 <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<div class="card mb-4">
 <div class="card-body">
 <form method="POST" class="row g-2">
 <div class="col-md-9">
 <input type="text" name="nom" class="form-control"
 placeholder="Nouvelle catégorie..." required>
 </div>
 <div class="col-md-3">
 <button type="submit" class="btn btn-primary w-100">Ajouter</button>
 </div>
 </form>
 </div>
</div>

<table class="table table-bordered">
 <thead class="table-dark">
 <tr><th>#</th><th>Nom</th><th>Slug</th><th>Action</th></tr>
 </thead>
 <tbody>
 <?php foreach ($categories as $c): ?>
 <tr>
 <td><?= $c['id'] ?></td>
 <td><?= htmlspecialchars($c['nom']) ?></td>
 <td><code><?= htmlspecialchars($c['slug']) ?></code></td>
 <td>
 <a href="<?= adminUrl('categories', ['delete' => $c['id']]) ?>"
 class="btn btn-sm btn-danger"
 onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
 </td>
 </tr>
 <?php endforeach; ?>
 </tbody>
</table>

<?php require_once ROOT . '/app/Views/layouts/admin_footer.php'; ?>