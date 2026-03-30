<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/helpers.php';

function getArticlesPublies(?int $limit = null): array {
 $pdo = getConnection();
 $sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
 FROM articles a
 JOIN categories c ON a.categorie_id = c.id
 JOIN statuts s ON a.statut_id = s.id
 WHERE s.libelle = 'publie'
 ORDER BY a.date_publication DESC";
 if ($limit !== null) $sql .= " LIMIT :limit";
 $stmt = $pdo->prepare($sql);
 if ($limit !== null) $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
 $stmt->execute();
 return $stmt->fetchAll();
}

function getArticleBySlug(string $slug): ?array {
 $pdo = getConnection();
 $stmt = $pdo->prepare("
 SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
 FROM articles a
 JOIN categories c ON a.categorie_id = c.id
 JOIN statuts s ON a.statut_id = s.id
 WHERE a.slug = :slug AND s.libelle = 'publie'
 ");
 $stmt->execute(['slug' => $slug]);
 return $stmt->fetch() ?: null;
}

/**
 * Récupérer un article par ID (pour URLs ID+SLUG)
 * Beaucoup plus rapide qu'une recherche par slug
 */
function getArticleById(int $id): ?array {
 $pdo = getConnection();
 $stmt = $pdo->prepare("
 SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
 FROM articles a
 JOIN categories c ON a.categorie_id = c.id
 JOIN statuts s ON a.statut_id = s.id
 WHERE a.id = :id AND s.libelle = 'publie'
 ");
 $stmt->execute(['id' => $id]);
 return $stmt->fetch() ?: null;
}

function getArticlesByCategorie(string $categorieSlug): array {
 $pdo = getConnection();
 $stmt = $pdo->prepare("
 SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
 FROM articles a
 JOIN categories c ON a.categorie_id = c.id
 JOIN statuts s ON a.statut_id = s.id
 WHERE c.slug = :slug AND s.libelle = 'publie'
 ORDER BY a.date_publication DESC
 ");
 $stmt->execute(['slug' => $categorieSlug]);
 return $stmt->fetchAll();
}

function getCategories(): array {
 return getConnection()->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();
}

function getCategorieBySlug(string $slug): ?array {
 $stmt = getConnection()->prepare("SELECT * FROM categories WHERE slug = :slug");
 $stmt->execute(['slug' => $slug]);
 return $stmt->fetch() ?: null;
}

function genererExtrait(string $contenu, int $longueur = 150): string {
 $texte = strip_tags($contenu);
 return strlen($texte) <= $longueur ? $texte : substr($texte, 0, $longueur) . '...';
}

function formaterDate(string $date): string {
 $mois = [1=>'janvier',2=>'février',3=>'mars',4=>'avril',5=>'mai',6=>'juin',
 7=>'juillet',8=>'août',9=>'septembre',10=>'octobre',11=>'novembre',12=>'décembre'];
 $ts = strtotime($date);
 return date('j', $ts) . ' ' . $mois[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function e(string $s): string {
 return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}