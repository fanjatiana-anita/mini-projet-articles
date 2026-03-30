# 📰 Iran News - Structure du Projet

## 📁 Architecture Finale (Réorganisée)

```
mini-projet-articles/
│
├── 📄 index.php                    ← Routeur FrontOffice (site public)
├── 📄 admin.php                    ← Routeur BackOffice (administration)
├── 🔗 .htaccess                    ← Réécriture d'URL
│
├── 📦 app/                         ← Logique métier (Models + Views)
│   ├── Models/                     ← Modèles (accès à la BDD)
│   │   ├── ArticleModel.php
│   │   ├── CategoryModel.php
│   │   ├── UserModel.php
│   │   └── DashboardModel.php
│   │
│   └── Views/                      ← Vues HTML/PHP
│       ├── front/                  ← FrontOffice
│       └── admin/                  ← BackOffice
│       └── layouts/                ← Layouts communs
│
├── 🎨 public/                      ← Fichiers publiques
│   ├── css/                        ← Feuille de style CSS
│   ├── js/                         ← JavaScript client
│   ├── images/                     ← Images statiques
│   └── uploads/                    ← Uploads utilisateur
│
├── ⚙️ config/                       ← Configuration
│   ├── database.php                ← Connexion MySQL
│   └── helpers.php                 ← Fonctions utilitaires
│
├── 📊 database/                    ← Scripts SQL
│   ├── data.sql                    ← Données de test
│   └── script.sql                  ← Structure
│
├── 🐳 docker/                      ← Configuration Docker
├── docker-compose.yml              ← Orchestration
└── README.md                       ← Ce fichier
```

## 🚀 Démarrage rapide

### FrontOffice (Public)
- **URL**: `http://localhost:8080/`
- **Fichier**: `index.php`
- **Routes**: `/`, `/articles`, `/article/{slug}`, `/categorie/{slug}`

### BackOffice (Admin)
- **URL**: `http://localhost:8081/admin.php`
- **Login par défaut**: `admin` / `admin123`
- **Routes**: dashboard, articles CRUD, catégories

## 📊 Base de Données

### Connexion
- **Host**: `db` (Docker) ou `localhost` (local)
- **Port**: 3306
- **Database**: `iran_news`
- **User**: `root`
- **Password**: `root`

### Tables
- **articles**: id, titre, slug, contenu, image, statut, date_publication, ...
- **categories**: id, nom, slug
- **utilisateurs**: id, username, email, mot_de_passe, role

## 🔧 Technologies

- **PHP 8.2+**
- **MySQL 8.0**
- **Bootstrap 5**
- **Docker**
- **Apache** avec mod_rewrite

## 📝 Configuration

Éditer `config/database.php` pour ajuster:
```php
$host = 'db';
$dbname = 'iran_news';
$user = 'root';
$password = 'root';
```

## ✨ Fonctionnalités

✅ Routeur FrontOffice/BackOffice  
✅ CRUD complet des articles et catégories  
✅ Authentification admin  
✅ Upload d'images  
✅ Génération automatique de slugs  
✅ Recherche d'articles  
✅ SEO (meta tags, alt images)  
✅ Responsive design  
✅ Docker prêt à l'emploi  

## 🐳 Docker

```bash
docker-compose up -d
```

Services:
- FrontOffice: `http://localhost:8080`
- BackOffice: `http://localhost:8081/admin.php`
- MySQL: localhost:3306
- PHPMyAdmin: `http://localhost:8082` (optionnel)
