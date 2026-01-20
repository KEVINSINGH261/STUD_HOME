# STUD_HOME - Gestion de Logements Étudiants

Plateforme web de mise en relation entre étudiants cherchant un logement et propriétaires proposant des biens immobiliers.

## Description du Projet

STUD_HOME est une application web développée en **PHP natif** (sans framework) suivant l'architecture **MVC (Modèle-Vue-Contrôleur)**. Elle permet aux étudiants de rechercher des logements et aux propriétaires de publier leurs annonces.

## Types d'Utilisateurs

### 1. Étudiants
- Inscription avec école
- Recherche de logements (par ville, prix, type)
- Ajout d'annonces en favoris
- Gestion de profil

### 2. Propriétaires
- Inscription avec téléphone
- Création d'annonces
- Modification/Suppression d'annonces
- Gestion de leurs biens

### 3. Administrateur
- Gestion des utilisateurs
- Validation/Suppression d'annonces
- Statistiques de la plateforme

## Architecture du Projet

```
STUD_HOME/
├── app/
│   ├── core/              # Classes fondamentales
│   │   ├── Router.php       # Routeur d'application
│   │   ├── Controller.php   # Contrôleur de base
│   │   ├── Model.php        # Modèle de base (PDO)
│   │   ├── Database.php     # Connexion base de données
│   │   └── Validator.php    # Validation côté serveur
│   ├── controllers/       # Contrôleurs
│   │   ├── AuthController.php           # Authentification & mots de passe
│   │   ├── HomeController.php           # Page d'accueil & équipe
│   │   ├── AnnonceController.php        # Annonces publiques
│   │   ├── EtudiantController.php       # Dashboard étudiant
│   │   ├── ProprietaireController.php   # Dashboard propriétaire
│   │   ├── AdminController.php          # Backoffice admin
│   │   └── FooterController.php         # Pages légales/cookies
│   └── models/           # Modèles métier
│       ├── Utilisateur.php      # Gestion utilisateurs
│       ├── Etudiant.php         # Profil étudiant
│       ├── Proprietaire.php     # Profil propriétaire
│       ├── Annonce.php          # Gestion annonces
│       ├── Favori.php           # Système favoris
│       └── PasswordReset.php    # Réinitialisation mot de passe
│
├── config/
│   ├── config.php        # Configuration générale
│   ├── database.php      # Configuration base de données
│   └── routes.php        # Définition des routes
│
├── database/
│   └── migrations/       # Scripts SQL
│       ├── create_database.sql       # Création base de données
│       ├── add_security_columns.sql  # Questions de sécurité
│       └── add_multiple_images.sql   # Support multi-images
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
├── PHPMailer-master/    # Bibliothèque envoi d'emails
│   ├── src/             # Classes PHPMailer
│   └── language/        # Traductions
│
├── storage/
│   └── logs/            # Logs d'application
│
└── views/               # Templates HTML
    ├── layout/          # Layout principal
    ├── partials/        # Composants (header, footer)
    ├── home/            # Pages d'accueil & équipe
    ├── auth/            # Authentification (login, register)
    ├── password-oublie/ # Récupération mot de passe
    ├── annonces/        # Liste & détails annonces
    ├── etudiant/        # Dashboard & favoris étudiant
    ├── proprietaire/    # Dashboard & gestion annonces
    ├── admin/           # Backoffice admin
    ├── lien-footer/     # Pages légales (cookies, CGU)
    └── pages/           # Pages statiques
```

## Installation

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

## Comptes de Test

### Administrateur
- **Email:** admin@studhome.fr
- **Mot de passe:** admin123

### Étudiant
- **Email:** jean.dupont@example.com
- **Mot de passe:** student123

### Propriétaire
- **Email:** pierre.leroux@example.com
- **Mot de passe:** owner123

## Fonctionnalités Principales

### Authentification
- Inscription séparée (Étudiant/Propriétaire)
- Question de sécurité à l'inscription
- Connexion sécurisée (hash password bcrypt)
- Récupération de mot de passe (question sécurité)
- Vérification email unique (AJAX)
- Déconnexion

### Gestion des Annonces
- Création/Modification/Suppression (Propriétaires)
- Upload multiple de photos (10 images max)
- Définition d'image principale
- Suppression individuelle d'images
- Recherche multicritères (ville, prix, type)
- Système de favoris (Étudiants)
- Validation par administrateur

### Administration
- Gestion des utilisateurs (suppression)
- Modération des annonces (validation/suppression)
- Statistiques de la plateforme

### Autres Fonctionnalités
- Gestion des préférences cookies
- Pages légales (paramètres cookies)
- Page équipe
- Validation côté serveur (classe Validator)

## Technologies Utilisées

- **Backend:** PHP 8+ (POO strict)
- **Base de données:** MySQL avec PDO
- **Frontend:** HTML5, CSS3, JavaScript vanilla
- **Architecture:** MVC Pattern (PHP natif)
- **Bibliothèques:**
  - PHPMailer - Envoi d'emails (récupération mot de passe)
- **Sécurité:** 
  - Password hashing (bcrypt)
  - Questions de sécurité (récupération MDP)
  - Protection XSS (htmlspecialchars + Validator)
  - Protection CSRF (tokens)
  - Requêtes préparées (PDO)
  - Validation côté serveur (classe Validator)
  - Upload sécurisé de fichiers

## Base de Données

### Tables
1. **utilisateurs** - Table parent avec question_securite
2. **etudiants** - Profils étudiants (ecole)
3. **proprietaires** - Profils propriétaires (telephone)
4. **annonces** - Logements proposés (multi-images)
5. **annonce_images** - Images des annonces
6. **favoris** - Relation Étudiant-Annonce
7. **password_resets** - Tokens récupération (optionnel)

### Relations
- Un étudiant/propriétaire **hérite de** utilisateur (1:1)
- Un propriétaire **possède plusieurs** annonces (1:N)
- Un étudiant **peut avoir plusieurs** favoris (N:M)

## Routes Principales

```
# Routes publiques
/                                    → Page d'accueil
/home                                → Page d'accueil
/equipe                              → Page équipe

# Authentification
/login                               → Connexion
/register                            → Inscription
/register/check-email                → Vérification email (AJAX)
/logout                              → Déconnexion

# Récupération mot de passe
/forgot-password                     → Formulaire récupération
/forgot-password-security            → Question de sécurité
/verify-security-answer              → Vérification réponse
/reset-password                      → Nouveau mot de passe

# Annonces (public)
/annonces                            → Liste des annonces
/annonces/search                     → Recherche
/annonces/details/{id}               → Détail d'une annonce

# Espace Étudiant
/etudiant/dashboard                  → Dashboard
/etudiant/favoris                    → Mes favoris
/etudiant/favoris/add/{id}           → Ajouter favori
/etudiant/favoris/remove/{id}        → Retirer favori
/etudiant/profile                    → Mon profil
/etudiant/profile/update             → Mise à jour profil

# Espace Propriétaire
/proprietaire/dashboard              → Dashboard
/proprietaire/annonces               → Mes annonces
/proprietaire/annonces/create        → Créer annonce
/proprietaire/annonces/edit/{id}     → Modifier annonce
/proprietaire/annonces/delete/{id}   → Supprimer annonce
/proprietaire/annonces/image/delete/{id}     → Supprimer image
/proprietaire/annonces/image/set-main/{id}   → Image principale
/proprietaire/profile                → Mon profil

# Espace Admin
/admin/dashboard                     → Dashboard admin
/admin/utilisateurs                  → Gestion utilisateurs
/admin/utilisateurs/delete/{id}      → Supprimer utilisateur
/admin/annonces                      → Gestion annonces
/admin/annonces/validate/{id}        → Valider annonce
/admin/annonces/delete/{id}          → Supprimer annonce
/admin/stats                         → Statistiques

# Pages légales
/parametres-cookies                  → Gestion cookies
/parametres-cookies/save             → Sauvegarder préférences
```

## Sécurité

- Mots de passe hashés (bcrypt avec PASSWORD_DEFAULT)
- Questions de sécurité (hashées) pour récupération MDP
- Protection contre les injections SQL (requêtes préparées PDO)
- Protection XSS (htmlspecialchars + classe Validator)
- Protection CSRF (tokens de session)
- Gestion des sessions sécurisée (regeneration ID)
- Validation côté serveur (classe Validator complète)
- Contrôle d'accès par rôle (vérification sessions)
- Upload de fichiers sécurisé (validation type/taille MIME)
- Vérification email unique (AJAX avant soumission)
- Sanitization des données utilisateur

## Licence

Projet étudiant - Tous droits réservés

## Développeurs

STUD_HOME - Promotion 2025

---

**Note:** Ce projet est développé dans un cadre pédagogique pour démontrer la maîtrise de PHP natif et de l'architecture MVC.
