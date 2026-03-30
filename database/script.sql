DROP DATABASE IF EXISTS iran_news;
CREATE DATABASE iran_news;
\c iran_news;

CREATE TABLE roles (
    id      SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE statuts (
    id      SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE utilisateurs (
    id           SERIAL PRIMARY KEY,
    role_id      INT NOT NULL,
    username     VARCHAR(50)  NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    CONSTRAINT fk_role FOREIGN KEY(role_id) REFERENCES roles(id)
);

CREATE TABLE categories (
    id    SERIAL PRIMARY KEY,
    nom   VARCHAR(100) NOT NULL,
    slug  VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE articles (
    id               SERIAL PRIMARY KEY,
    categorie_id     INT NOT NULL,
    statut_id        INT NOT NULL DEFAULT 2,
    titre            VARCHAR(255) NOT NULL,
    slug             VARCHAR(255) NOT NULL UNIQUE,
    contenu          TEXT NOT NULL,
    image_principale VARCHAR(255),
    alt_image        VARCHAR(255),
    meta_description VARCHAR(160),
    date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted       BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_categorie FOREIGN KEY(categorie_id) REFERENCES categories(id),
    CONSTRAINT fk_statut    FOREIGN KEY(statut_id)    REFERENCES statuts(id)
);

-- Table des types d'actions (create, update, delete, restore)
CREATE TABLE actions (
    id      SERIAL PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table d'historique : trace CHAQUE modification d'article
CREATE TABLE articles_history (
    id                SERIAL PRIMARY KEY,
    article_id        INT NOT NULL,
    action_id         INT NOT NULL,
    titre             TEXT,
    contenu           TEXT,
    slug              VARCHAR(255),
    categorie_id      INT,
    statut_id         INT,
    image_principale  VARCHAR(255),
    alt_image         VARCHAR(255),
    meta_description  VARCHAR(160),
    date_publication  TIMESTAMP,
    modifie_par       INT,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_article FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
    CONSTRAINT fk_action FOREIGN KEY(action_id) REFERENCES actions(id),
    CONSTRAINT fk_user FOREIGN KEY(modifie_par) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

INSERT INTO roles    (libelle) VALUES ('admin'), ('editeur');
INSERT INTO statuts  (libelle) VALUES ('publie'), ('brouillon');
INSERT INTO actions  (libelle) VALUES ('create'), ('update'), ('delete'), ('restore');

INSERT INTO categories (nom, slug) VALUES
    ('Politique',  'politique'),
    ('Militaire',  'militaire'),
    ('Diplomatie', 'diplomatie');

-- admin / admin123
INSERT INTO utilisateurs (role_id, username, mot_de_passe) VALUES
    (1, 'admin', '$2y$10$hc8t2aBSaG3bh4bQItcTHuSHfAj1/YdFTDmG2Rd4Zktaua033RktC');

-- Les images pointent vers /mini-projet-articles/public/images/

-- Les images pointent vers images/ (chemin relatif à public/)
INSERT INTO articles (categorie_id, statut_id, titre, slug, contenu, image_principale, alt_image, meta_description) VALUES

(1, 1, 'Les sanctions internationales contre l Iran',
 'sanctions-internationales-contre-iran',
 '<h2>Impact des sanctions</h2><p>Les sanctions économiques imposées à l Iran ont profondément affecté l économie du pays depuis 2018.</p><h3>Conséquences économiques</h3><p>Le rial iranien a perdu plus de 60% de sa valeur, entraînant une inflation record et une baisse du niveau de vie.</p>',
 'images/sanctions.jpg',
 'Manifestation contre les sanctions économiques en Iran',
 'Analyse des sanctions internationales contre l Iran et leurs effets sur la population.'),

(1, 1, 'Le gouvernement iranien face à la crise',
 'gouvernement-iranien-face-crise',
 '<h2>Une crise politique majeure</h2><p>Le gouvernement iranien fait face à une pression croissante de la communauté internationale sur son programme nucléaire.</p><h3>Position officielle</h3><p>Les autorités maintiennent que leur programme est exclusivement civil et pacifique.</p>',
 'images/gouvernement.jpg',
 'Bâtiment du parlement iranien à Téhéran',
 'Le gouvernement iranien et sa gestion de la crise politique et nucléaire en 2024.'),

(2, 1, 'Les forces armées iraniennes en 2024',
 'forces-armees-iraniennes-2024',
 '<h2>Capacités militaires</h2><p>L Iran possède l une des armées les plus importantes du Moyen-Orient avec plus de 500 000 soldats.</p><h3>Les Gardiens de la Révolution</h3><p>Ils constituent la force d élite du régime iranien disposant de missiles balistiques avancés.</p>',
 'images/militaires.jpg',
 'Défilé militaire des forces armées iraniennes',
 'État des forces armées iraniennes et leurs capacités militaires en 2024.'),

(2, 1, 'Programme de missiles balistiques iranien',
 'programme-missiles-balistiques-iranien',
 '<h2>Un arsenal en développement</h2><p>Le programme de missiles iranien est l un des plus avancés de la région avec une portée de plus de 2000 km.</p><h3>Implications régionales</h3><p>Ces capacités modifient l équilibre des forces au Moyen-Orient et préoccupent la communauté internationale.</p>',
 'images/missiles.jpg',
 'Lancement d un missile balistique iranien lors d un exercice militaire',
 'Le programme de missiles balistiques de l Iran et ses implications pour la sécurité régionale.'),

(3, 1, 'Les négociations nucléaires à Vienne',
 'negociations-nucleaires-vienne',
 '<h2>Retour à la table des négociations</h2><p>Les négociations sur le nucléaire iranien ont repris à Vienne avec les grandes puissances mondiales.</p><h3>Points de blocage</h3><p>Le niveau d enrichissement autorisé et la levée des sanctions restent les principaux sujets de désaccord entre les parties.</p>',
 'images/vienne.jpg',
 'Salle de conférence lors des négociations nucléaires à Vienne',
 'Suivi des négociations nucléaires entre l Iran et les grandes puissances à Vienne.'),

(3, 1, 'Relations Iran et Union Européenne',
 'relations-iran-union-europeenne',
 '<h2>Un dialogue difficile</h2><p>Les relations entre l Iran et l Union Européenne sont marquées par des tensions persistantes autour du nucléaire et des droits de l homme.</p><h3>Médiation européenne</h3><p>L UE joue un rôle de médiateur entre Téhéran et Washington pour maintenir un dialogue ouvert.</p>',
 'images/ue-iran.jpg',
 'Drapeaux de l Union Européenne et de l Iran lors d une conférence diplomatique',
 'État des relations diplomatiques entre l Iran et l Union Européenne en 2024.');