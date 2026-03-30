<?php
class DashboardModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getStats(): array {
        return [
            'nb_articles'   => (int)$this->pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn(),
            'nb_categories' => (int)$this->pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
            'nb_publies'    => (int)$this->pdo->query("SELECT COUNT(*) FROM articles WHERE statut_id=1")->fetchColumn(),
        ];
    }

    public function getLast5(): array {
        return $this->pdo->query("
            SELECT a.titre, a.date_publication, s.libelle AS statut
            FROM articles a JOIN statuts s ON a.statut_id=s.id
            ORDER BY a.date_publication DESC LIMIT 5
        ")->fetchAll();
    }
}