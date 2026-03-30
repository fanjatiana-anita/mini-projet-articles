<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/config/slug.php';
require_once ROOT . '/app/Models/ArticleModel.php';
require_once ROOT . '/app/Views/layouts/admin_header.php';

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
    $statut_id = intval($_POST['statut_id'] ?? 2);
    $alt = trim($_POST['alt_image'] ?? '');
    $meta = trim($_POST['meta_description'] ?? '');
    $date_publication = trim($_POST['date_publication'] ?? date('Y-m-d'));
    $slug = generer_slug($titre);
    $image_path = '';

    if (!empty($_FILES['image']['name'])) {
        $image_path = uploadImage($_FILES['image'], 'images');
        if (empty($image_path)) {
            $erreur = 'Erreur lors de l\'upload. Formats: JPG, PNG, GIF, WebP. Max: 5MB.';
        }
    }

    if ($titre && $contenu && $cat_id && empty($erreur)) {
        try {
            $model->create([
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
            $erreur = "Erreur BDD (slug unique?): " . $e->getMessage();
        }
    } else if (empty($erreur)) {
        $erreur = 'Titre, contenu et catégorie obligatoires.';
    }
}
?>

<h1 class="h3 mb-4">Nouvel article</h1>

<?php require ROOT . '/app/Views/admin/articles/form.php'; ?>

<?php require_once ROOT . '/app/Views/layouts/admin_footer.php'; ?>
