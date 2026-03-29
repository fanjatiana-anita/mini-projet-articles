-- ============================================================
--  DONNÉES : on remplit les tables de référence en premier
--  (roles et statuts avant utilisateurs et articles)
-- ============================================================

-- Roles
INSERT INTO roles (libelle) VALUES
    ('admin'),    -- id=1
    ('editeur');  -- id=2

-- Statuts
INSERT INTO statuts (libelle) VALUES
    ('publie'),    -- id=1
    ('brouillon'); -- id=2

-- Categories
INSERT INTO categories (nom, slug) VALUES
    ('Politique',  'politique'),   -- id=1
    ('Militaire',  'militaire'),   -- id=2
    ('Diplomatie', 'diplomatie');  -- id=3

-- Utilisateur admin
-- username : admin
-- password : admin123  (hashé avec password_hash)
INSERT INTO utilisateurs (role_id, username, mot_de_passe) VALUES
    (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Articles (statut_id=1 = publie)
INSERT INTO articles (categorie_id, statut_id, titre, slug, contenu, image_principale, alt_image, meta_description) VALUES

(1, 1,
 'Les sanctions internationales contre l Iran',
 'sanctions-internationales-contre-iran',
 '<h2>Impact des sanctions</h2><p>Les sanctions économiques imposées à l Iran ont profondément affecté l économie du pays depuis 2018.</p><h3>Conséquences économiques</h3><p>Le rial iranien a perdu plus de 60% de sa valeur, entraînant une inflation record.</p>',
 'uploads/sanctions.jpg',
 'Manifestation contre les sanctions économiques en Iran',
 'Analyse des sanctions internationales contre l Iran et leurs effets sur la population iranienne.'),

(1, 1,
 'Le gouvernement iranien face à la crise',
 'gouvernement-iranien-face-crise',
 '<h2>Une crise politique majeure</h2><p>Le gouvernement iranien fait face à une pression croissante de la communauté internationale.</p><h3>Position officielle</h3><p>Les autorités maintiennent que leur programme nucléaire est exclusivement civil.</p>',
 'uploads/gouvernement.jpg',
 'Bâtiment du parlement iranien à Téhéran',
 'Le gouvernement iranien et sa gestion de la crise politique et nucléaire en 2024.'),

(2, 1,
 'Les forces armées iraniennes en 2024',
 'forces-armees-iraniennes-2024',
 '<h2>Capacités militaires</h2><p>L Iran possède l une des armées les plus importantes du Moyen-Orient avec plus de 500 000 soldats.</p><h3>Les Gardiens de la Révolution</h3><p>Ils constituent la force d élite du régime.</p>',
 'uploads/militaire.jpg',
 'Défilé militaire des forces armées iraniennes',
 'État des forces armées iraniennes et leurs capacités militaires en 2024.'),

(2, 1,
 'Programme de missiles balistiques iranien',
 'programme-missiles-balistiques-iranien',
 '<h2>Un arsenal en développement</h2><p>Le programme de missiles iranien est l un des plus avancés de la région.</p><h3>Portée</h3><p>L Iran dispose de missiles capables d atteindre des cibles à plus de 2000 km.</p>',
 'uploads/missiles.jpg',
 'Lancement d un missile balistique iranien lors d un exercice militaire',
 'Le programme de missiles balistiques de l Iran et ses implications pour la sécurité régionale.'),

(3, 1,
 'Les négociations nucléaires à Vienne',
 'negociations-nucleaires-vienne',
 '<h2>Retour à la table des négociations</h2><p>Les négociations sur le nucléaire iranien ont repris à Vienne avec les grandes puissances.</p><h3>Points de blocage</h3><p>Le niveau d enrichissement autorisé reste le principal sujet de désaccord.</p>',
 'uploads/vienne.jpg',
 'Salle de conférence lors des négociations nucléaires à Vienne',
 'Suivi des négociations nucléaires entre l Iran et les grandes puissances mondiales à Vienne.'),

(3, 1,
 'Relations Iran et Union Européenne',
 'relations-iran-union-europeenne',
 '<h2>Un dialogue difficile</h2><p>Les relations entre l Iran et l Union Européenne sont marquées par des tensions persistantes.</p><h3>Médiation européenne</h3><p>L UE joue un rôle de médiateur entre l Iran et les États-Unis.</p>',
 'uploads/ue-iran.jpg',
 'Drapeaux de l Union Européenne et de l Iran lors d une conférence diplomatique',
 'État des relations diplomatiques entre l Iran et l Union Européenne en 2024.');
