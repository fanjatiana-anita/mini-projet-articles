<?php
// Pas de session_start() ici — déjà fait dans admin.php
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
 <title>Connexion — BackOffice Iran News</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="min-height:100vh">
<div style="width:380px">
 <div class="card shadow-lg">
 <div class="card-body p-4">
 <h1 class="h4 text-center mb-4">Connexion BackOffice</h1>
 <?php if ($erreur): ?>
 <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
 <?php endif; ?>
 <form method="POST">
 <div class="mb-3">
 <label class="form-label">Nom d'utilisateur</label>
 <input type="text" name="username" class="form-control" value="admin">
 </div>
 <div class="mb-3">
 <label class="form-label">Mot de passe</label>
 <input type="password" name="password" class="form-control" value="admin123" required>
 </div>
 <button type="submit" class="btn btn-primary w-100">Se connecter</button>
 </form>
 <div class="text-center mt-3">
 <small class="text-muted">admin / admin123</small>
 </div>
 </div>
 </div>
</div>
</body>
</html>