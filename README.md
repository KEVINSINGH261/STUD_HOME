# STUD_HOME - Gestion de Logements Étudiants

Plateforme web de mise en relation entre étudiants cherchant un logement et propriétaires proposant des biens immobiliers.

## 🎯 Description du Projet

STUD_HOME est une application web développée en **PHP natif** (sans framework) suivant l'architecture **MVC (Modèle-Vue-Contrôleur)**. Elle permet aux étudiants de rechercher des logements et aux propriétaires de publier leurs annonces.

## 👥 Types d'Utilisateurs

### 1. Étudiants
- ✅ Inscription avec école
- 🔍 Recherche de logements (par ville, prix, type)
- ❤️ Ajout d'annonces en favoris
- 👤 Gestion de profil

### 2. Propriétaires
- ✅ Inscription avec téléphone
- ➕ Création d'annonces
- ✏️ Modification/Suppression d'annonces
- 📊 Gestion de leurs biens

### 3. Administrateur
- 👥 Gestion des utilisateurs
- 🏠 Validation/Suppression d'annonces
- 📈 Statistiques de la plateforme

## 🏗️ Architecture du Projet

```
STUD_HOME/
├── app/
│   ├── core/              # Classes fondamentales (Router, Controller, Model, Database)
│   ├── controllers/       # Contrôleurs (Auth, Home, Annonce, Etudiant, Proprietaire, Admin)
│   ├── models/           # Modèles métier (Utilisateur, Etudiant, Proprietaire, Annonce, Favori)
│   ├── helpers/          # Fonctions utilitaires
│   └── middlewares/      # Middlewares (authentification, validation)
│
├── config/
│   ├── config.php        # Configuration générale
│   ├── database.php      # Configuration base de données
│   └── routes.php        # Définition des routes
│
├── database/
│   └── migrations/       # Scripts SQL
│       └── create_database.sql
│
├── public/               # Point d'entrée web (Document Root)
│   ├── index.php        # Front Controller
│   ├── .htaccess        # Réécriture d'URL
│   ├── css/
│   │   └── style.css    # Styles CSS
│   ├── js/
│   │   └── main.js      # JavaScript
│   ├── images/          # Images statiques
│   └── uploads/         # Fichiers uploadés
│       └── annonces/
│
├── storage/
│   └── logs/            # Logs d'application
│
└── views/               # Templates HTML
    ├── layout/          # Layout principal
    ├── partials/        # Composants (header, footer)
    ├── home/            # Pages d'accueil
    ├── auth/            # Authentification
    ├── annonces/        # Annonces
    ├── etudiant/        # Dashboard étudiant
    ├── proprietaire/    # Dashboard propriétaire
    └── admin/           # Backoffice admin
```

## 🚀 Installation

### Prérequis
- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)
- Extension PDO PHP activée

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone https://github.com/KEVINSINGH261/STUD_HOME.git
cd STUD_HOME
```

2. **Configurer la base de données**
```bash
# Créer la base de données MySQL
mysql -u root -p < database/migrations/create_database.sql
```

3. **Configurer l'application**
```php
# Éditer config/database.php
return [
    'host' => 'localhost',
    'dbname' => 'stud_home_db',
    'username' => 'root',
    'password' => '',  // Votre mot de passe MySQL
];
```

4. **Configurer Apache**

Le dossier `public/` doit être le DocumentRoot de votre serveur.

**Option A: Virtual Host**
```apache
<VirtualHost *:80>
    ServerName studhome.local
    DocumentRoot "C:/chemin/vers/STUD_HOME/public"
    <Directory "C:/chemin/vers/STUD_HOME/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Option B: Serveur PHP intégré (développement uniquement)**
```bash
cd public
php -S localhost:8000
```

5. **Accéder à l'application**
```
http://localhost/STUD_HOME/public
ou
http://studhome.local (si Virtual Host configuré)
```

## 👤 Comptes de Test

### Administrateur
- **Email:** admin@studhome.fr
- **Mot de passe:** admin123

### Étudiant
- **Email:** jean.dupont@example.com
- **Mot de passe:** student123

### Propriétaire
- **Email:** pierre.leroux@example.com
- **Mot de passe:** owner123

## 🔑 Fonctionnalités Principales

### Authentification
- ✅ Inscription séparée (Étudiant/Propriétaire)
- 🔐 Connexion sécurisée (hash password)
- 🚪 Déconnexion

### Gestion des Annonces
- 📝 Création/Modification/Suppression (Propriétaires)
- 🔍 Recherche multicritères (ville, prix, type)
- 📷 Upload de photos
- ❤️ Système de favoris (Étudiants)

### Administration
- 👥 Gestion des utilisateurs
- 🏠 Modération des annonces
- 📊 Statistiques

## 🛠️ Technologies Utilisées

- **Backend:** PHP 8+ (POO strict)
- **Base de données:** MySQL avec PDO
- **Frontend:** HTML5, CSS3, JavaScript
- **Architecture:** MVC Pattern
- **Sécurité:** 
  - Password hashing (bcrypt)
  - Protection XSS (htmlspecialchars)
  - Requêtes préparées (PDO)
  - Validation des données

## 📊 Base de Données

### Tables
1. **utilisateurs** - Table parent (héritage)
2. **etudiants** - Profils étudiants (ecole)
3. **proprietaires** - Profils propriétaires (telephone)
4. **annonces** - Logements proposés
5. **favoris** - Relation Étudiant-Annonce

### Relations
- Un étudiant/propriétaire **hérite de** utilisateur (1:1)
- Un propriétaire **possède plusieurs** annonces (1:N)
- Un étudiant **peut avoir plusieurs** favoris (N:M)

## 📝 Routes Principales

```
/                           → Page d'accueil
/login                      → Connexion
/register                   → Inscription
/annonces                   → Liste des annonces
/annonces/details/{id}      → Détail d'une annonce
/etudiant/dashboard         → Dashboard étudiant
/proprietaire/dashboard     → Dashboard propriétaire
/admin/dashboard            → Backoffice admin
```

## 🔒 Sécurité

- ✅ Mots de passe hashés (bcrypt)
- ✅ Protection contre les injections SQL (PDO préparé)
- ✅ Protection XSS (sanitization)
- ✅ Gestion des sessions sécurisée
- ✅ Validation des données côté serveur
- ✅ Contrôle d'accès par rôle (middleware)

## 📄 Licence

Projet étudiant - Tous droits réservés

## 👨‍💻 Développeurs

STUD_HOME - Promotion 2025

---

**Note:** Ce projet est développé dans un cadre pédagogique pour démontrer la maîtrise de PHP natif et de l'architecture MVC.
