<?php
// Pas de session_start() ici — deja fait dans admin.php
require_once ROOT . '/config/database.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
            header('Location: ' . BASE_URL_ADMIN . '/admin.php?page=dashboard');
            exit;
        }
        $erreur = 'Identifiants incorrects.';
    } else {
        $erreur = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Accédez de manière sécurisée au panneau d'administration Iran News. Connexion réservée aux administrateurs.">
    <!-- <meta name="theme-color" content="#667eea"> -->
    <title>Connexion — BackOffice Iran News</title>

    <!-- Préconnexion DNS pour améliorer les performances -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Bootstrap CSS avec SRI pour la sécurité -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">

    <!-- CSS personnalisé avec préchargement -->
    <link rel="preload" href="<?= BASE_URL ?>/public/css/admin.css" as="style">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
</head>
<body class="login-page">
    <main role="main" aria-labelledby="login-title">
        <div class="login-card">
            <h1 id="login-title" class="h4 text-center mb-4">
                <span aria-hidden="true"></span> Connexion BackOffice
            </h1>

            <?php if ($erreur): ?>
            <div class="admin-alert admin-alert-danger" role="alert" aria-live="assertive">
                <strong>Erreur :</strong> <?= htmlspecialchars($erreur) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="admin-form" aria-label="Formulaire de connexion">
                <div class="mb-3">
                    <label for="username" class="form-label">
                        Nom d'utilisateur <span class="required" aria-hidden="true">*</span>
                        <span class="visually-hidden">(obligatoire)</span>
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        autocomplete="username"
                        required
                        aria-required="true"
                        aria-describedby="username-help"
                    >
                    <div id="username-help" class="form-text">Entrez votre identifiant</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Mot de passe <span class="required" aria-hidden="true">*</span>
                        <span class="visually-hidden">(obligatoire)</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        autocomplete="current-password"
                        required
                        aria-required="true"
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Se connecter
                </button>
            </form>

            <p class="text-center mt-3 mb-0">
                <small class="text-muted" aria-label="Identifiants par defaut">
                    Demo: admin / admin123
                </small>
            </p>
        </div>
    </main>
</body>
</html>
