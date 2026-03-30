<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/Models/ArticleModel.php';

$model = new ArticleModel($pdo);
$id = intval($_GET['id'] ?? 0);

// Récupérer l'article
$article = $model->getById($id);
if (!$article) {
    header('Location: ' . adminUrl('articles'));
    exit;
}

// Traiter la suppression POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if ($model->delete($id)) {
        $_SESSION['success'] = "Article supprimé avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression.";
    }
    header('Location: ' . adminUrl('articles'));
    exit;
}

// Traiter l'annulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    header('Location: ' . adminUrl('articles'));
    exit;
}

// Afficher la page de confirmation
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer article - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Confirmer la suppression</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <strong>Attention!</strong> Cette action marquera l'article comme supprimé mais conservera les données dans l'historique pour traçabilité.
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-muted">Article à supprimer:</h6>
                            <p class="mb-1"><strong><?= htmlspecialchars($article['titre']) ?></strong></p>
                            <small class="text-muted">ID: <?= $article['id'] ?></small>
                        </div>
                        
                        <hr>
                        
                        <form method="POST">
                            <div class="d-grid gap-2">
                                <button type="submit" name="confirm_delete" value="1" class="btn btn-danger btn-lg">
                                    Oui, supprimer cet article
                                </button>
                                <button type="submit" name="cancel" value="1" class="btn btn-secondary btn-lg">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>