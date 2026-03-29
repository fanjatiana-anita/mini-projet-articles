<?php
/**
 * Fonctions utilitaires pour le frontoffice
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Récupère tous les articles publiés
 * @param int|null $limit Nombre d'articles à récupérer (null = tous)
 * @return array Liste des articles
 */
function getArticlesPublies(?int $limit = null): array
{
    $pdo = getConnection();
    $sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts s ON a.statut_id = s.id
            WHERE s.libelle = 'publie'
            ORDER BY a.date_publication DESC";

    if ($limit !== null) {
        $sql .= " LIMIT :limit";
    }

    $stmt = $pdo->prepare($sql);
    if ($limit !== null) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll();
}

/**
 * Récupère un article par son slug
 * @param string $slug Slug de l'article
 * @return array|null Article ou null si non trouvé
 */
function getArticleBySlug(string $slug): ?array
{
    $pdo = getConnection();
    $sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts s ON a.statut_id = s.id
            WHERE a.slug = :slug AND s.libelle = 'publie'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['slug' => $slug]);

    $article = $stmt->fetch();
    return $article ?: null;
}

/**
 * Récupère tous les articles d'une catégorie
 * @param string $categorieSlug Slug de la catégorie
 * @return array Liste des articles
 */
function getArticlesByCategorie(string $categorieSlug): array
{
    $pdo = getConnection();
    $sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts s ON a.statut_id = s.id
            WHERE c.slug = :slug AND s.libelle = 'publie'
            ORDER BY a.date_publication DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['slug' => $categorieSlug]);

    return $stmt->fetchAll();
}

/**
 * Récupère toutes les catégories
 * @return array Liste des catégories
 */
function getCategories(): array
{
    $pdo = getConnection();
    $sql = "SELECT * FROM categories ORDER BY nom ASC";
    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}

/**
 * Récupère une catégorie par son slug
 * @param string $slug Slug de la catégorie
 * @return array|null Catégorie ou null si non trouvée
 */
function getCategorieBySlug(string $slug): ?array
{
    $pdo = getConnection();
    $sql = "SELECT * FROM categories WHERE slug = :slug";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['slug' => $slug]);

    $categorie = $stmt->fetch();
    return $categorie ?: null;
}

/**
 * Génère un extrait à partir du contenu HTML
 * @param string $contenu Contenu HTML
 * @param int $longueur Longueur maximale de l'extrait
 * @return string Extrait texte
 */
function genererExtrait(string $contenu, int $longueur = 150): string
{
    $texte = strip_tags($contenu);
    if (strlen($texte) <= $longueur) {
        return $texte;
    }
    return substr($texte, 0, $longueur) . '...';
}

/**
 * Formate une date en français
 * @param string $date Date au format SQL
 * @return string Date formatée
 */
function formaterDate(string $date): string
{
    $timestamp = strtotime($date);
    $mois = [
        1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
        5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
        9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
    ];

    $jour = date('j', $timestamp);
    $moisNum = (int)date('n', $timestamp);
    $annee = date('Y', $timestamp);

    return $jour . ' ' . $mois[$moisNum] . ' ' . $annee;
}

/**
 * Échappe les caractères spéciaux pour éviter les failles XSS
 * @param string $string Chaîne à échapper
 * @return string Chaîne échappée
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
