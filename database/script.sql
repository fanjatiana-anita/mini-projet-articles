CREATE DATABASE IF NOT EXISTS iran_news CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iran_news;


CREATE TABLE roles (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL 
);



CREATE TABLE statuts (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL   -- "publie" ou "brouillon"
);


CREATE TABLE utilisateurs (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    role_id      INT NOT NULL,
    username     VARCHAR(50)  NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,          -- hashé avec password_hash()

    FOREIGN KEY (role_id) REFERENCES roles(id)
);


CREATE TABLE categories (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nom   VARCHAR(100) NOT NULL,
    slug  VARCHAR(100) NOT NULL UNIQUE
);


CREATE TABLE articles (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    categorie_id     INT NOT NULL,
    statut_id        INT NOT NULL DEFAULT 2,      -- 2=brouillon par défaut
    titre            VARCHAR(255) NOT NULL,
    slug             VARCHAR(255) NOT NULL UNIQUE,
    contenu          LONGTEXT NOT NULL,            -- HTML généré par TinyMCE
    image_principale VARCHAR(255),
    alt_image        VARCHAR(255),
    meta_description VARCHAR(160),
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    FOREIGN KEY (statut_id)    REFERENCES statuts(id)
);