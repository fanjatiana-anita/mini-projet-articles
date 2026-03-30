<?php
/**
 * Fichier de diagnostic - À supprimer après utilisation
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnostic du projet</h1>";

// 1. Vérifier les extensions PHP
echo "<h2>1. Extensions PHP</h2>";
echo "<ul>";
echo "<li>pdo_pgsql: " . (extension_loaded('pdo_pgsql') ? '<span style="color:green">OK</span>' : '<span style="color:red">NON CHARGÉ - Activez dans php.ini</span>') . "</li>";
echo "<li>pgsql: " . (extension_loaded('pgsql') ? '<span style="color:green">OK</span>' : '<span style="color:red">NON CHARGÉ</span>') . "</li>";
echo "<li>pdo_mysql: " . (extension_loaded('pdo_mysql') ? '<span style="color:green">OK</span>' : '<span style="color:red">NON</span>') . "</li>";
echo "</ul>";

// 2. Tester la connexion PostgreSQL
echo "<h2>2. Connexion PostgreSQL</h2>";
try {
    $host = 'localhost';
    $port = '5432';
    $dbname = 'iran_news';
    $user = 'postgres';
    $password = 'olivia';

    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Connexion PostgreSQL réussie!</p>";

    // 3. Tester les catégories
    echo "<h2>3. Catégories dans la base</h2>";
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($categories)) {
        echo "<p style='color:orange'>Aucune catégorie trouvée. Exécutez le script SQL.</p>";
    } else {
        echo "<ul>";
        foreach ($categories as $cat) {
            echo "<li>" . htmlspecialchars($cat['nom']) . " (" . htmlspecialchars($cat['slug']) . ")</li>";
        }
        echo "</ul>";
    }

    // 4. Tester les articles
    echo "<h2>4. Articles dans la base</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM articles WHERE is_deleted = false");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Nombre d'articles: " . $count['total'] . "</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Erreur de connexion: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>Solutions possibles:</h3>";
    echo "<ul>";
    echo "<li>Vérifiez que PostgreSQL est démarré</li>";
    echo "<li>Activez pdo_pgsql dans C:\\xampp\\php\\php.ini</li>";
    echo "<li>Vérifiez les identifiants de connexion</li>";
    echo "</ul>";
}

// 5. Vérifier BASE_URL
echo "<h2>5. BASE_URL</h2>";
$script = $_SERVER['SCRIPT_NAME'] ?? '';
$base = preg_replace('#/(index|router|admin|diagnostic)\\.php$#', '', $script);
if ($base === '') $base = '/';
$baseUrl = rtrim($base, '/');
echo "<p>BASE_URL calculé: <code>" . htmlspecialchars($baseUrl) . "</code></p>";
echo "<p>URL attendue: <code>/mini-projet-articles</code></p>";

echo "<hr><p><em>Supprimez ce fichier après diagnostic.</em></p>";
