<?php
class ArticleModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Enregistrer une action dans l'historique
     */
    private function logAction(int $articleId, string $actionName, array $data): bool {
        // Récupérer l'ID de l'action
        $stmt = $this->pdo->prepare("SELECT id FROM actions WHERE libelle = ?");
        $stmt->execute([$actionName]);
        $actionId = $stmt->fetchColumn();
        
        if (!$actionId) {
            return false;
        }

        // Enregistrer dans l'historique
        return $this->pdo->prepare("
            INSERT INTO articles_history 
                (article_id, action_id, titre, contenu, slug, categorie_id, statut_id, 
                 image_principale, alt_image, meta_description, date_publication)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ")->execute([
            $articleId,
            $actionId,
            $data['titre'] ?? null,
            $data['contenu'] ?? null,
            $data['slug'] ?? null,
            $data['categorie_id'] ?? null,
            $data['statut_id'] ?? null,
            $data['image_principale'] ?? null,
            $data['alt_image'] ?? null,
            $data['meta_description'] ?? null,
            $data['date_publication'] ?? null
        ]);
    }

    // ========================================
    // ARTICLES PUBLICS (non supprimés)
    // ========================================

    public function getAll(): array {
        return $this->pdo->query("
            SELECT a.*, c.nom AS categorie, s.libelle AS statut
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts    s ON a.statut_id    = s.id
            WHERE a.is_deleted = FALSE
            ORDER BY a.date_publication DESC
        ")->fetchAll();
    }

    public function getPublished(): array {
        return $this->pdo->query("
            SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts    s ON a.statut_id    = s.id
            WHERE s.libelle = 'publie' AND a.is_deleted = FALSE
            ORDER BY a.date_publication DESC
        ")->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT * FROM articles 
            WHERE id = ? AND is_deleted = FALSE
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getBySlug(string $slug): array|false {
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts    s ON a.statut_id    = s.id
            WHERE a.slug = ? AND s.libelle = 'publie' AND a.is_deleted = FALSE
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getByCategory(string $slug): array {
        $stmt = $this->pdo->prepare("
            SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts    s ON a.statut_id    = s.id
            WHERE c.slug = ? AND s.libelle = 'publie' AND a.is_deleted = FALSE
            ORDER BY a.date_publication DESC
        ");
        $stmt->execute([$slug]);
        return $stmt->fetchAll();
    }

    // ========================================
    // OPÉRATIONS AVEC HISTORIQUE
    // ========================================

    public function create(array $d): bool {
        // Déterminer l'ID séquence
        $stmt = $this->pdo->prepare("SELECT NEXTVAL('articles_id_seq') as id");
        $stmt->execute();
        $articleId = (int)$stmt->fetch()['id'];
        
        // Insérer l'article
        $success = $this->pdo->prepare("
            INSERT INTO articles
                (id, categorie_id, statut_id, titre, slug, contenu, image_principale, alt_image, meta_description, date_publication, is_deleted)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE)
        ")->execute([
            $articleId,
            $d['categorie_id'],
            $d['statut_id'] ?? 1,
            $d['titre'],
            $d['slug'],
            $d['contenu'],
            $d['image_principale'] ?? null,
            $d['alt_image'] ?? null,
            $d['meta_description'] ?? null,
            $d['date_publication'] ?? date('Y-m-d')
        ]);
        
        if ($success) {
            // Enregistrer dans l'historique
            $this->logAction($articleId, 'create', $d);
        }
        
        return $success;
    }

    public function update(int $id, array $d): bool {
        // Mettre à jour l'article
        $success = $this->pdo->prepare("
            UPDATE articles SET
                categorie_id=?, statut_id=?, titre=?, slug=?, contenu=?,
                image_principale=?, alt_image=?, meta_description=?, date_publication=?
            WHERE id=? AND is_deleted = FALSE
        ")->execute([
            $d['categorie_id'],
            $d['statut_id'] ?? 1,
            $d['titre'],
            $d['slug'],
            $d['contenu'],
            $d['image_principale'] ?? null,
            $d['alt_image'] ?? null,
            $d['meta_description'] ?? null,
            $d['date_publication'] ?? date('Y-m-d'),
            $id
        ]);
        
        if ($success) {
            // Enregistrer dans l'historique
            $this->logAction($id, 'update', $d);
        }
        
        return $success;
    }

    public function delete(int $id): bool {
        // Soft delete : marquer comme supprimé
        $success = $this->pdo->prepare("
            UPDATE articles SET is_deleted = TRUE WHERE id=?
        ")->execute([$id]);
        
        if ($success) {
            // Enregistrer dans l'historique
            $this->logAction($id, 'delete', []);
        }
        
        return $success;
    }

    public function restore(int $id): bool {
        // Restaurer un article supprimé
        $success = $this->pdo->prepare("
            UPDATE articles SET is_deleted = FALSE WHERE id=?
        ")->execute([$id]);
        
        if ($success) {
            // Enregistrer dans l'historique
            $this->logAction($id, 'restore', []);
        }
        
        return $success;
    }

    // ========================================
    // ARTICLES SUPPRIMÉS (admin only)
    // ========================================

    public function getDeleted(): array {
        return $this->pdo->query("
            SELECT a.*, c.nom AS categorie, s.libelle AS statut
            FROM articles a
            JOIN categories c ON a.categorie_id = c.id
            JOIN statuts    s ON a.statut_id    = s.id
            WHERE a.is_deleted = TRUE
            ORDER BY a.date_publication DESC
        ")->fetchAll();
    }

    public function getHistory(int $articleId): array {
        return $this->pdo->query("
            SELECT ah.*, ac.libelle AS action_name
            FROM articles_history ah
            JOIN actions ac ON ah.action_id = ac.id
            WHERE ah.article_id = $articleId
            ORDER BY ah.created_at DESC
        ")->fetchAll();
    }

    // ========================================
    // COMPTAGE & DERNIERS ARTICLES
    // ========================================

    public function count(): int {
        return (int)$this->pdo->query("
            SELECT COUNT(*) FROM articles WHERE is_deleted = FALSE
        ")->fetchColumn();
    }

    public function getLast5(): array {
        return $this->pdo->query("
            SELECT a.titre, a.date_publication, s.libelle AS statut
            FROM articles a 
            JOIN statuts s ON a.statut_id=s.id
            WHERE a.is_deleted = FALSE
            ORDER BY a.date_publication DESC LIMIT 5
        ")->fetchAll();
    }

    // ========================================
    // CATÉGORIES & STATUTS
    // ========================================

    public function getAllCategories(): array {
        return $this->pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
    }

    public function getAllStatuts(): array {
        return $this->pdo->query("SELECT * FROM statuts")->fetchAll();
    }

    public function createCategory(string $nom, string $slug): bool {
        return $this->pdo->prepare("INSERT INTO categories (nom,slug) VALUES (?,?)")->execute([$nom,$slug]);
    }

    public function countArticlesInCategory(int $id): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM articles WHERE categorie_id = ? AND is_deleted = FALSE");
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn();
    }

    public function deleteCategory(int $id): bool {
        if ($this->countArticlesInCategory($id) > 0) {
            throw new RuntimeException('Impossible de supprimer cette catégorie : des articles y sont encore rattachés.');
        }
        return $this->pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
    }

    public function getCategoryBySlug(string $slug): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE slug=?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}