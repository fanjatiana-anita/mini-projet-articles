<?php
/**
 * CategoryModel — Gestion des catégories
 */
class CategoryModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Toutes les catégories
     */
    public function getAll(): array {
        return $this->pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
    }

    /**
     * Une catégorie par son slug
     */
    public function getBySlug(string $slug): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    /**
     * Créer une catégorie
     */
    public function create(string $nom, string $slug): bool {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nom, slug) VALUES (?, ?)");
        return $stmt->execute([$nom, $slug]);
    }

    /**
     * Modifier une catégorie
     */
    public function update(int $id, string $nom, string $slug): bool {
        $stmt = $this->pdo->prepare("UPDATE categories SET nom = ?, slug = ? WHERE id = ?");
        return $stmt->execute([$nom, $slug, $id]);
    }

    /**
     * Supprimer une catégorie
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Nombre total de catégories
     */
    public function count(): int {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    }

    /**
     * Une catégorie par son ID
     */
    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Vérifier si un slug existe
     */
    public function slugExists(string $slug, int $excludeId = 0): bool {
        if ($excludeId > 0) {
            $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        return $stmt->fetch() !== false;
    }
}
