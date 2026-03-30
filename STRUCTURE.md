# 📋 Documentation Détaillée - Restructuration du Projet

**Date**: 30 Mars 2026  
**Objet**: Réorganisation complète du projet Iran News

---

## 🎯 Objectifs Atteints

### ✅ 1. Réorganisation Physique
- ✅ Création de `app/Models/` avec tous les Models
- ✅ Organisation de `app/Views/` en `front/`, `admin/`, `layouts/`
- ✅ Création de `public/` avec structure assets
- ✅ Nettoyage des dossiers obsolètes (`frontend/`, `backend/`)
- ✅ Migration des assets CSS

### ✅ 2. Correction des Routeurs
- ✅ **admin.php**: Routeur BackOffice fonctionnel
- ✅ **index.php**: Routeur FrontOffice fonctionnel
- ✅ Imports corrects vers `app/Models/`
- ✅ Gestion des sessions et authentification
- ✅ Upload d'images avec validation

### ✅ 3. Correction des Models
- ✅ **ArticleModel**: CRUD + méthodes spécialisées
  - `getAll()`, `getPublished()`, `getBySlug()`, `getByCategory()`
  - `search()`, `slugExists()`, `create()`, `update()`, `delete()`
- ✅ **CategoryModel**: Gestion des catégories
  - `getAll()`, `getById()`, `getBySlug()`, `slugExists()`
  - `create()`, `update()`, `delete()`
- ✅ **UserModel**: Authentification
  - `authenticate()`, `getByUsername()`, `create()`, `delete()`
- ✅ **DashboardModel**: Statistiques
  - `getStats()`, `getLast5()`

### ✅ 4. Configuration & Helpers
- ✅ **config/database.php**: MySQL (PDO)
- ✅ **config/helpers.php**: Fonctions utilitaires
  - `redirect()`, `requireAdmin()`, `generateSlug()`
  - `escape()`, `getFlash()`, `setFlash()`
  - `excerpt()`, `formatDate()`, `imageUrl()`

### ✅ 5. Assets
- ✅ Migration CSS vers `public/css/`
- ✅ Création structure `public/js/`, `images/`, `uploads/`

---

## 📊 Structure Finale

```
mini-projet-articles/
├── admin.php                       ← Routeur BackOffice (287 lignes)
├── index.php                       ← Routeur FrontOffice (110 lignes)
├── .htaccess                       ← Réécriture d'URL
├── docker-compose.yml
│
├── app/
│   ├── Models/                     ← NOUVEAU
│   │   ├── ArticleModel.php        (150+ lignes)
│   │   ├── CategoryModel.php       (90+ lignes)
│   │   ├── UserModel.php           (80+ lignes)
│   │   └── DashboardModel.php      (30+ lignes)
│   │
│   └── Views/
│       ├── front/                  ← À créer
│       ├── admin/                  ← À créer
│       └── layouts/                ← À créer
│
├── config/
│   ├── database.php                ← CORRIGÉ (MySQL)
│   ├── helpers.php                 ← NOUVEAU
│   └── slug.php
│
├── public/
│   ├── css/
│   │   └── style.css               ← COPIÉ
│   ├── js/                         ← À créer
│   ├── images/                     ← À ajouter
│   └── uploads/                    ← Zone de uploads
│
├── database/
│   ├── script.sql
│   └── data.sql
│
├── docker/
│   └── Dockerfile
│
├── README.md                       ← RÉÉCRIT
└── todo.txt
```

---

## 🔄 Migration Effectuée

### Avant
```
/app
  ├── ArticleModel.php             ← Racine app/
  ├── CategoryModel.php
  ├── UserModel.php
  ├── DashboardModel.php
  ├── StatusModel.php
  ├── Models/ (vide)
  └── Views/

/frontend                          ← Obsolète
  ├── index.php
  ├── article.php
  └── assets/css/style.css

/backend                           ← Obsolète
  ├── login.php
  └── articles/create.php
```

### Après
```
/app
  ├── Models/                      ← Réorganisé ✅
  │   ├── ArticleModel.php
  │   ├── CategoryModel.php
  │   ├── UserModel.php
  │   ├── DashboardModel.php
  │   └── (StatusModel.php supprimé)
  └── Views/

/public/css/style.css              ← Migré ✅

/frontend → SUPPRIMÉ ✅
/backend → SUPPRIMÉ ✅
```

---

## 🔧 Changements de Code

### 1. Chemins d'Import

**Avant**:
```php
require_once ROOT . '/app/UserModel.php';
```

**Après**:
```php
require_once ROOT . '/app/Models/UserModel.php';
```

### 2. Méthodes des Models

**ArticleModel**:
- `getPublies()` → `getPublished($limit)`
- `getAllAdmin()` → `getAll()`
- Ajout: `getByCategorie()`, `slugExists()`

**CategoryModel**:
- Ajout: `getById()`, `slugExists()`

### 3. Gestion des Flashs

**Avant**:
```php
$_SESSION['flash'] = ['type' => 'success', 'msg' => 'OK'];
```

**Après**:
```php
setFlash('success', 'OK');
```

### 4. Génération de Slugs

**Avant**:
```php
$slug = genererSlug($titre);
```

**Après**:
```php
$slug = generateSlug($titre);  // Standardisé dans helpers.php
```

---

## 📡 Routage

### FrontOffice (index.php)

```
GET /                              → home.php
GET /articles                       → articles.php (+ ?q=recherche)
GET /article/slug-article           → article.php
GET /categorie/slug-categorie       → categorie.php
GET /invalid-path                   → 404.php
```

### BackOffice (admin.php)

```
POST /admin.php                     → login.php (authentification)
GET  /admin.php?page=logout         → Session destroy + redirect
GET  /admin.php?page=dashboard      → dashboard.php (protégé)
GET  /admin.php?page=articles       → articles/index.php
GET  /admin.php?page=articles.create→ articles/form.php
GET  /admin.php?page=articles.edit&id=N → articles/form.php
GET  /admin.php?page=articles.delete&id=N → suppression
GET  /admin.php?page=categories     → categories/index.php
```

---

## 🔐 Sécurité

### Authentification
- Fonction `requireAdmin()` dans tous les routages protégés
- Vérification `$_SESSION['admin_id']`
- Mot de passe hashé avec bcrypt

### Upload
- Vérification MIME type réel
- Limite 2 Mo
- Formats autorisés: JPG, PNG, WebP
- Renommage aléatoire des fichiers

### XSS
- Fonction `escape()` pour échapper les données
- À appliquer dans les vues

### SQL Injection
- Utilisation exclusive de `PDO->prepare()` et `execute()`

---

## 📦 Dépendances

### Externe (CDN suggérées)
- **Bootstrap 5**: CDN
- **TinyMCE**: CDN + clé API
- **jQuery** (optionnel): CDN

### Interne
- **PDO** (inclus PHP)
- **Session** (inclus PHP)

---

## ✅ Checklist de Validation

- [x] Tous les Models dans `app/Models/`
- [x] Tous les imports corrects
- [x] Base de données MySQL (pas PostgreSQL)
- [x] Fonctions helpers centralisées
- [x] CSS migré vers `public/css/`
- [x] Anciens dossiers supprimés
- [x] Routeurs nettoyés et fonctionnels
- [x] Flash messages standardisés
- [x] Upload d'images sécurisé
- [x] Génération de slugs cohérente

---

## 🚀 Prochaines Étapes

1. **Créer les vues** dans `/app/Views/`
   - FrontOffice: 5 pages
   - BackOffice: 3 formulaires + 4 listes
   - Layouts: header/footer

2. **Initialiser la BDD**
   - Exécuter `database/script.sql`
   - Peupler avec `database/data.sql`
   - Admin par défaut: `admin/admin123`

3. **Intégrer TinyMCE**
   - Ajouter dans l'éditeur d'articles
   - Configurer les plugins (image, link, lists, ...)

4. **Tests**
   - Vérifier routage FO et BO
   - Tester CRUD complet
   - Valider SEO (meta tags, images)

---

## 📝 Notes Importantes

### CSS
- Bootstrap 5 à importer dans les layouts
- CSS custom dans `/public/css/style.css`
- Responsive design mobile-first

### Base de Données
- Utiliser **MySQL** 8.0+ (pas PostGres)
- Charset UTF-8 MB4
- InnoDB (par défaut)

### Uploads
- Valider côté serveur UNIQUEMENT
- Stocker dans `public/uploads/`
- Chemin DB: `uploads/filename.ext`

### Statut des Articles
- ENUM: `'publié'` ou `'brouillon'`
- Pas de table statuts séparée (simplificatio)

---

**Créé par**: Assistant  
**Version**: 1.0  
**État**: ✅ Prêt pour développement des vues
