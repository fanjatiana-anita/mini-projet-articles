-- ============================================================
--  STRUCTURE DE LA BASE DE DONNÉES - PostgreSQL
--  Base de données : iran_news
-- ============================================================

-- Supprimer les tables si elles existent (pour réinitialisation)
DROP TABLE IF EXISTS articles CASCADE;
DROP TABLE IF EXISTS utilisateurs CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS statuts CASCADE;
DROP TABLE IF EXISTS roles CASCADE;

-- Table des rôles
CREATE TABLE roles (
    id      SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- Table des statuts
CREATE TABLE statuts (
    id      SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL   -- "publie" ou "brouillon"
);

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id           SERIAL PRIMARY KEY,
    role_id      INT NOT NULL,
    username     VARCHAR(50)  NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,          -- hashé avec password_hash()

    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Table des catégories
CREATE TABLE categories (
    id    SERIAL PRIMARY KEY,
    nom   VARCHAR(100) NOT NULL,
    slug  VARCHAR(100) NOT NULL UNIQUE
);

-- Table des articles
CREATE TABLE articles (
    id               SERIAL PRIMARY KEY,
    categorie_id     INT NOT NULL,
    statut_id        INT NOT NULL DEFAULT 2,      -- 2=brouillon par défaut
    titre            VARCHAR(255) NOT NULL,
    slug             VARCHAR(255) NOT NULL UNIQUE,
    contenu          TEXT NOT NULL,               -- HTML généré par TinyMCE
    image_principale VARCHAR(255),
    alt_image        VARCHAR(255),
    meta_description VARCHAR(160),
    date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    FOREIGN KEY (statut_id)    REFERENCES statuts(id)
);

-- Index pour améliorer les performances
CREATE INDEX idx_articles_categorie ON articles(categorie_id);
CREATE INDEX idx_articles_statut ON articles(statut_id);
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_categories_slug ON categories(slug);
