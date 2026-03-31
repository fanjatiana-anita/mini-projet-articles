-- ============================================================
--  DONNÉES : on remplit les tables de référence en premier
--  (roles et statuts avant utilisateurs et articles)
-- ============================================================

INSERT INTO roles (libelle) VALUES
    ('admin'),    -- id=1
    ('editeur')   -- id=2
ON CONFLICT DO NOTHING;

INSERT INTO statuts (libelle) VALUES
    ('publie'),    -- id=1
    ('brouillon')  -- id=2
ON CONFLICT DO NOTHING;

INSERT INTO categories (nom, slug) VALUES
    ('Politique',  'politique'),   -- id=1
    ('Militaire',  'militaire'),   -- id=2
    ('Diplomatie', 'diplomatie')   -- id=3
ON CONFLICT DO NOTHING;

-- Utilisateur admin
-- username : admin
-- password : admin123  (hashé avec password_hash)
INSERT INTO utilisateurs (role_id, username, mot_de_passe) VALUES
    (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON CONFLICT DO NOTHING;

-- Articles (statut_id=1 = publie)
INSERT INTO articles (categorie_id, statut_id, titre, slug, contenu, image_principale, alt_image, meta_description) VALUES

(1, 1,
 'Les sanctions internationales contre l''Iran',
 'sanctions-internationales-contre-iran',
 '<h2>Impact des sanctions</h2><p>Les sanctions economiques imposees a l''Iran ont profondement affecte l''economie du pays depuis 2018.</p><h3>Consequences economiques</h3><p>Le rial iranien a perdu plus de 60% de sa valeur, entrainant une inflation record.</p>',
 'assets/images/sanctions.jpg',
 'Manifestation contre les sanctions economiques en Iran',
 'Analyse des sanctions internationales contre l''Iran et leurs effets sur la population iranienne.'),

(1, 1,
 'Le gouvernement iranien face a la crise',
 'gouvernement-iranien-face-crise',
 '<h2>Une crise politique majeure</h2><p>Le gouvernement iranien fait face a une pression croissante de la communaute internationale.</p><h3>Position officielle</h3><p>Les autorites maintiennent que leur programme nucleaire est exclusivement civil.</p>',
 'assets/images/gouvernement.jpg',
 'Batiment du parlement iranien a Teheran',
 'Le gouvernement iranien et sa gestion de la crise politique et nucleaire en 2024.'),

(2, 1,
 'Les forces armees iraniennes en 2024',
 'forces-armees-iraniennes-2024',
 '<h2>Capacites militaires</h2><p>L''Iran possede l''une des armees les plus importantes du Moyen-Orient avec plus de 500 000 soldats.</p><h3>Les Gardiens de la Revolution</h3><p>Ils constituent la force d''elite du regime.</p>',
 'assets/images/millitaires.jpg',
 'Defile militaire des forces armees iraniennes',
 'Etat des forces armees iraniennes et leurs capacites militaires en 2024.'),

(2, 1,
 'Programme de missiles balistiques iranien',
 'programme-missiles-balistiques-iranien',
 '<h2>Un arsenal en developpement</h2><p>Le programme de missiles iranien est l''un des plus avances de la region.</p><h3>Portee</h3><p>L''Iran dispose de missiles capables d''atteindre des cibles a plus de 2000 km.</p>',
 'assets/images/missiles.jpg',
 'Lancement d''un missile balistique iranien lors d''un exercice militaire',
 'Le programme de missiles balistiques de l''Iran et ses implications pour la securite regionale.'),

(3, 1,
 'Les negociations nucleaires a Vienne',
 'negociations-nucleaires-vienne',
 '<h2>Retour a la table des negociations</h2><p>Les negociations sur le nucleaire iranien ont repris a Vienne avec les grandes puissances.</p><h3>Points de blocage</h3><p>Le niveau d''enrichissement autorise reste le principal sujet de desaccord.</p>',
 'assets/images/vienne.jpg',
 'Salle de conference lors des negociations nucleaires a Vienne',
 'Suivi des negociations nucleaires entre l''Iran et les grandes puissances mondiales a Vienne.'),

(3, 1,
 'Relations Iran et Union Europeenne',
 'relations-iran-union-europeenne',
 '<h2>Un dialogue difficile</h2><p>Les relations entre l''Iran et l''Union Europeenne sont marquees par des tensions persistantes.</p><h3>Mediation europeenne</h3><p>L''UE joue un role de mediateur entre l''Iran et les Etats-Unis.</p>',
 'assets/images/ue-iran.jpg',
 'Drapeaux de l''Union Europeenne et de l''Iran lors d''une conference diplomatique',
'Etat des relations diplomatiques entre l''Iran et l''Union Europeenne en 2024.')
ON CONFLICT (slug) DO NOTHING;

