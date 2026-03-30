<?php
/**
 * UserModel — Gestion des utilisateurs
 */
class UserModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer un utilisateur par son username
     */
    public function getByUsername(string $username): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    /**
     * Vérifier le mot de passe et retourner l'utilisateur
     */
    public function authenticate(string $username, string $password): array|false {
        $user = $this->getByUsername($username);
        
        if (!$user) {
            return false;
        }

        // Vérifier le mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }

        return false;
    }

    /**
     * Tous les utilisateurs
     */
    public function getAll(): array {
        return $this->pdo->query("SELECT id, username, email, role FROM utilisateurs")->fetchAll();
    }

    /**
     * Un utilisateur par son ID
     */
    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT id, username, email, role FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Créer un utilisateur
     */
    public function create(string $username, string $email, string $password, string $role = 'editeur'): bool {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateurs (username, email, mot_de_passe, role)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$username, $email, $hashedPassword, $role]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
