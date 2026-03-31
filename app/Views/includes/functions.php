<?php
/**
 * Fonctions utilitaires FrontOffice
 * NB : articleUrl(), categorieUrl(), imageUrl(), e(), genererSlug(),
 *      formaterDate(), genererExtrait() sont dans config/helpers.php
 *      — ne pas les redéclarer ici.
 */

require_once ROOT . '/config/database.php';
require_once ROOT . '/config/helpers.php';

// ── Requêtes articles ─────────────────────────────────────────

function getArticlesPublies(?int $limit = null): array
{
    $pdo = getConnection();
    $sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts    s ON a.statut_id    = s.id
            WHERE s.libelle = 'publie'
            ORDER BY a.date_publication DESC";
    if ($limit !== null) $sql .= ' LIMIT :limit';
    $stmt = $pdo->prepare($sql);
    if ($limit !== null) $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getArticleBySlug(string $slug): ?array
{
    $stmt = getConnection()->prepare(
        "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
         FROM articles a
         JOIN categories c ON a.categorie_id = c.id
         JOIN statuts    s ON a.statut_id    = s.id
         WHERE a.slug = :slug AND s.libelle = 'publie'"
    );
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch() ?: null;
}

function getArticleById(int $id): ?array
{
    $stmt = getConnection()->prepare(
        "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
         FROM articles a
         JOIN categories c ON a.categorie_id = c.id
         JOIN statuts    s ON a.statut_id    = s.id
         WHERE a.id = :id AND s.libelle = 'publie'"
    );
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

function getArticlesByCategorie(string $categorieSlug): array
{
    $stmt = getConnection()->prepare(
        "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
         FROM articles a
         JOIN categories c ON a.categorie_id = c.id
         JOIN statuts    s ON a.statut_id    = s.id
         WHERE c.slug = :slug AND s.libelle = 'publie'
         ORDER BY a.date_publication DESC"
    );
    $stmt->execute(['slug' => $categorieSlug]);
    return $stmt->fetchAll();
}

function rechercherArticles(string $q): array
{
    $stmt = getConnection()->prepare(
        "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
         FROM articles a
         JOIN categories c ON a.categorie_id = c.id
         JOIN statuts    s ON a.statut_id    = s.id
         WHERE s.libelle = 'publie'
           AND (a.titre ILIKE :q OR a.contenu ILIKE :q OR a.meta_description ILIKE :q)
         ORDER BY a.date_publication DESC"
    );
    $stmt->execute(['q' => '%' . $q . '%']);
    return $stmt->fetchAll();
}

// ── Requêtes catégories ───────────────────────────────────────

function getCategories(): array
{
    return getConnection()->query(
        "SELECT * FROM categories ORDER BY nom ASC"
    )->fetchAll();
}

function getCategorieBySlug(string $slug): ?array
{
    $stmt = getConnection()->prepare(
        "SELECT * FROM categories WHERE slug = :slug"
    );
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch() ?: null;
}

function getCategoriesAvecNb(): array
{
    return getConnection()->query(
        "SELECT c.*, COUNT(a.id) AS nb_articles
         FROM categories c
         LEFT JOIN articles a ON a.categorie_id = c.id
         LEFT JOIN statuts  s ON a.statut_id    = s.id AND s.libelle = 'publie'
         GROUP BY c.id, c.nom, c.slug
         ORDER BY c.nom ASC"
    )->fetchAll();
}