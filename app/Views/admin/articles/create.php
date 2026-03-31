<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/config/slug.php';
require_once ROOT . '/app/Models/ArticleModel.php';

$model = new ArticleModel($pdo);
$categories = $model->getAllCategories();
$statuts = $model->getAllStatuts();
$erreur = '';
$article = null;
$isEdit = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = $_POST['contenu'] ?? '';
    $cat_id = intval($_POST['categorie_id'] ?? 0);
    $statut_id = intval($_POST['statut_id'] ?? 1);
    $alt = trim($_POST['alt_image'] ?? '');
    $meta = trim($_POST['meta_description'] ?? '');
    $date_publication = trim($_POST['date_publication'] ?? date('Y-m-d'));
    $slug = generer_slug($titre);
    $image_path = '';

    // Validation des champs obligatoires
    if (empty($titre) || empty($contenu) || empty($cat_id) || empty($meta) || empty($alt) || empty($date_publication)) {
        $erreur = 'Tous les champs marqués * sont obligatoires.';
    }

    // Upload image (obligatoire en création)
    if (empty($erreur) && !empty($_FILES['image']['name'])) {
        $image_path = uploadImage($_FILES['image'], 'images');
        if (empty($image_path)) {
            $erreur = 'Erreur lors de l\'upload. Formats: JPG, PNG, GIF, WebP. Max: 5MB.';
        }
    } elseif (empty($erreur) && empty($_FILES['image']['name'])) {
        $erreur = 'L\'image principale est obligatoire.';
    }

    if (empty($erreur)) {
        try {
            $model->create([
                'categorie_id' => $cat_id,
                'statut_id' => $statut_id,
                'titre' => $titre,
                'slug' => $slug,
                'contenu' => $contenu,
                'image_principale' => $image_path,
                'alt_image' => $alt,
                'meta_description' => $meta,
                'date_publication' => $date_publication,
            ]);
            header('Location: ' . adminUrl('articles'));
            exit;
        } catch (PDOException $e) {
            $erreur = "Erreur BDD (slug unique?): " . $e->getMessage();
        }
    }
}
?>

<?php require_once ROOT . '/app/Views/layouts/admin_header.php'; ?>

<!-- Page Header avec style moderne -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-1">
            <i class="bi bi-plus-circle me-2" aria-hidden="true"></i>
            Nouvel Article
        </h1>
        <p class="text-muted mb-0">
            <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
            Créez un nouvel article pour Iran News
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
