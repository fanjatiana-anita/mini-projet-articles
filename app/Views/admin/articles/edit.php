<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/config/slug.php';
require_once ROOT . '/app/Models/ArticleModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { 
    header('Location: ' . adminUrl('articles'));
    exit;
}

$model = new ArticleModel($pdo);
$article = $model->getById($id);
if (!$article) {
    header('Location: ' . adminUrl('articles'));
    exit;
}

$categories = $model->getAllCategories();
$statuts = $model->getAllStatuts();
$erreur = '';
$isEdit = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = $_POST['contenu'] ?? '';
    $cat_id = intval($_POST['categorie_id'] ?? 0);
    $statut_id = intval($_POST['statut_id'] ?? 2);
    $alt = trim($_POST['alt_image'] ?? '');
    $meta = trim($_POST['meta_description'] ?? '');
    $date_publication = trim($_POST['date_publication'] ?? $article['date_publication']);
    $slug = generer_slug($titre);
    $image_path = $article['image_principale'];
    $delete_image = isset($_POST['delete_image']) && $_POST['delete_image'] === 'on';

    // Gérer la suppression de l'image existante
    if ($delete_image) {
        $image_path = null;
    }

    // Upload d'une nouvelle image (remplace ou ajoute)
    if (!empty($_FILES['image']['name'])) {
        $new_image = uploadImage($_FILES['image'], 'images');
        if ($new_image) {
            $image_path = $new_image;
        } else {
            $erreur = 'Erreur lors de l\'upload. Formats: JPG, PNG, GIF, WebP. Max: 5MB.';
        }
    }

    if ($titre && $contenu && $cat_id && empty($erreur)) {
        try {
            $model->update($id, [
                'categorie_id' => $cat_id,
                'statut_id' => $statut_id,
                'titre' => $titre,
                'slug' => $slug,
                'contenu' => $contenu,
                'image_principale' => $image_path ?: NULL,
                'alt_image' => $alt ?: NULL,
                'meta_description' => $meta ?: NULL,
                'date_publication' => $date_publication,
            ]);
            header('Location: ' . adminUrl('articles'));
            exit;
        } catch (PDOException $e) {
            $erreur = "Erreur: " . $e->getMessage();
        }
    } else if (empty($erreur)) {
        $erreur = 'Titre, contenu et catégorie obligatoires.';
    }
}
?>

<!-- Page Header avec style moderne -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1">
            <i class="bi bi-pencil-square me-2" aria-hidden="true"></i>
            Modifier l'article
        </h1>
        <p class="text-muted mb-0">
            <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
            <?= htmlspecialchars($article['titre']) ?>
        </p>
    </div>
    <div>
        <a href="<?= adminUrl('articles') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
            Retour à la liste
        </a>
    </div>
</div>

<?php require ROOT . '/app/Views/admin/articles/form.php'; ?>

<?php require_once ROOT . '/app/Views/layouts/admin_footer.php'; ?>
