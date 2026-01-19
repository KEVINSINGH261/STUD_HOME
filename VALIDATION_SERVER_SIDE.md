# Guide de Validation - Classe Validator

## Vue d'ensemble

La classe `Validator` fournit un système de validation côté serveur robuste et sécurisé pour tous les formulaires de l'application STUD_HOME. Elle protège contre les injections XSS, valide les formats de données et garantit l'intégrité des informations.

## Utilisation de base

```php
// 1. Créer une instance avec les données POST
$validator = new Validator($_POST);

// 2. Chaîner les règles de validation
$validator->required('email', 'Email')
          ->email('email')
          ->required('nom', 'Nom')
          ->minLength('nom', 2, 'Nom')
          ->maxLength('nom', 50, 'Nom');

// 3. Vérifier si la validation a réussi
if (!$validator->isValid()) {
    // Récupérer les erreurs
    $errors = $validator->getErrorsAsString();
    // Afficher les erreurs
    $this->setFlash('error', $errors);
    return;
}

// 4. Récupérer les données validées et nettoyées
$validatedData = $validator->getValidatedData();
```

## Règles de validation disponibles

### Règles de base

#### `required($field, $label)`
Vérifie que le champ n'est pas vide.
```php
$validator->required('titre', 'Titre');
```

#### `minLength($field, $min, $label)`
Longueur minimale d'une chaîne.
```php
$validator->minLength('description', 20, 'Description');
```

#### `maxLength($field, $max, $label)`
Longueur maximale d'une chaîne.
```php
$validator->maxLength('titre', 200, 'Titre');
```

### Validation de formats

#### `email($field, $label)`
Valide un format d'email.
```php
$validator->email('email');
```

#### `phone($field, $label)`
Valide un numéro de téléphone français.
```php
$validator->phone('telephone');
// Accepte: 06 12 34 56 78, +33 6 12 34 56 78, 0612345678
```

#### `postalCode($field, $label)`
Valide un code postal français (5 chiffres).
```php
$validator->postalCode('code_postal');
```

### Validation de nombres

#### `integer($field, $label)`
Vérifie que la valeur est un entier.
```php
$validator->integer('chambres', 'Nombre de chambres');
```

#### `numeric($field, $label)`
Vérifie que la valeur est numérique positive.
```php
$validator->numeric('prix', 'Prix');
```

#### `min($field, $min, $label)`
Valeur minimale.
```php
$validator->min('prix', 1, 'Prix');
```

#### `max($field, $max, $label)`
Valeur maximale.
```php
$validator->max('prix', 10000, 'Prix');
```

### Validation de listes

#### `in($field, $values, $label)`
Vérifie que la valeur est dans une liste autorisée.
```php
$validator->in('type', ['Studio', 'T1', 'T2', 'T3'], 'Type de logement');
```

### Validation de fichiers

#### `file($field, $label)`
Vérifie qu'un fichier a été uploadé.
```php
$validator->file('photo', 'Photo');
```

#### `mimeType($field, $mimes, $label)`
Valide le type MIME d'un fichier.
```php
$validator->mimeType('photo', ['image/jpeg', 'image/png', 'image/webp'], 'Photo');
```

#### `fileSize($field, $maxSize, $label)`
Limite la taille d'un fichier (en octets).
```php
$validator->fileSize('photo', 5 * 1024 * 1024, 'Photo'); // 5 MB max
```

### Validation avancée

#### `matches($field, $matchField, $label)`
Vérifie que deux champs sont identiques.
```php
$validator->matches('password_confirm', 'password', 'Confirmation du mot de passe');
```

#### `regex($field, $pattern, $message)`
Validation avec expression régulière.
```php
$validator->regex('nom', '/^[a-zA-ZÀ-ÿ\s\-]+$/', 'Le nom ne doit contenir que des lettres.');
```

#### `custom($field, $callback, $message)`
Validation personnalisée avec une fonction.
```php
$validator->custom('email', function($value, $data) use ($utilisateurModel) {
    return !$utilisateurModel->emailExists($value);
}, 'Cet email est déjà utilisé.');
```

## Exemples complets

### Validation d'un formulaire d'annonce

```php
public function storeAnnonce(): void
{
    if (!$this->isPost()) {
        $this->redirect('proprietaire/annonces/create');
        return;
    }
    
    // Validation
    $validator = new Validator($_POST);
    
    $validator->required('titre', 'Titre')
              ->minLength('titre', 5, 'Titre')
              ->maxLength('titre', 200, 'Titre')
              
              ->required('description', 'Description')
              ->minLength('description', 20, 'Description')
              ->maxLength('description', 5000, 'Description')
              
              ->required('type', 'Type de logement')
              ->in('type', ['Studio', 'T1', 'T2', 'T3', 'T4'], 'Type')
              
              ->required('ville', 'Ville')
              ->minLength('ville', 2, 'Ville')
              
              ->required('prix', 'Prix')
              ->numeric('prix', 'Prix')
              ->min('prix', 1, 'Prix')
              ->max('prix', 10000, 'Prix');
    
    // Validations conditionnelles
    if (!empty($_POST['code_postal'])) {
        $validator->postalCode('code_postal');
    }
    
    if (!empty($_POST['surface'])) {
        $validator->integer('surface', 'Surface')
                  ->min('surface', 1, 'Surface');
    }
    
    // Vérifier les erreurs
    if (!$validator->isValid()) {
        $this->setFlash('error', $validator->getErrorsAsString());
        $this->redirect('proprietaire/annonces/create');
        return;
    }
    
    // Récupérer les données nettoyées (protection XSS)
    $data = $validator->getValidatedData();
    
    // Utiliser les données validées
    $annonceModel->create($data);
}
```

### Validation d'un formulaire de profil

```php
public function updateProfile(): void
{
    if (!$this->isPost()) {
        $this->redirect('profile');
        return;
    }
    
    $validator = new Validator($_POST);
    
    $validator->required('nom', 'Nom')
              ->minLength('nom', 2, 'Nom')
              ->maxLength('nom', 50, 'Nom')
              ->regex('nom', '/^[a-zA-ZÀ-ÿ\s\-]+$/', 'Le nom contient des caractères invalides.')
              
              ->required('prenom', 'Prénom')
              ->minLength('prenom', 2, 'Prénom')
              ->maxLength('prenom', 50, 'Prénom')
              
              ->required('email', 'Email')
              ->email('email')
              
              ->required('telephone', 'Téléphone')
              ->phone('telephone');
    
    // Validation personnalisée - email unique
    $utilisateurModel = $this->model('Utilisateur');
    $existingUser = $utilisateurModel->findByEmail($_POST['email']);
    if ($existingUser && $existingUser['id'] != $this->getUserId()) {
        $validator->addError('email', 'Cet email est déjà utilisé.');
    }
    
    if (!$validator->isValid()) {
        $this->setFlash('error', $validator->getErrorsAsString());
        $this->redirect('profile');
        return;
    }
    
    $data = $validator->getValidatedData();
    $utilisateurModel->update($this->getUserId(), $data);
}
```

### Validation de fichiers uploadés

```php
public function uploadPhoto(): void
{
    if (!$this->isPost()) {
        return;
    }
    
    $validator = new Validator([]);
    
    // Validation du fichier
    $validator->file('photo', 'Photo')
              ->mimeType('photo', ['image/jpeg', 'image/png', 'image/webp'], 'Photo')
              ->fileSize('photo', 5 * 1024 * 1024, 'Photo'); // 5 MB
    
    if (!$validator->isValid()) {
        $this->setFlash('error', $validator->getErrorsAsString());
        $this->redirect('annonces/create');
        return;
    }
    
    // Traiter l'upload
    move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
}
```

## Gestion des erreurs

### Récupérer toutes les erreurs

```php
// Tableau de toutes les erreurs
$errors = $validator->getErrors();
// Résultat: ['email' => ['Email est invalide.'], 'nom' => ['Nom est requis.']]

// Chaîne de caractères avec toutes les erreurs
$errorsString = $validator->getErrorsAsString(); // Séparateur par défaut: <br>
$errorsString = $validator->getErrorsAsString(', '); // Séparateur personnalisé
```

### Récupérer les erreurs d'un champ spécifique

```php
$emailErrors = $validator->getError('email');
// Résultat: ['Email est invalide.']
```

### Ajouter une erreur manuellement

```php
$validator->addError('email', 'Cet email existe déjà dans la base de données.');
```

## Sécurité

### Protection XSS automatique

La méthode `getValidatedData()` nettoie automatiquement toutes les chaînes avec `htmlspecialchars()` :

```php
$data = $validator->getValidatedData();
// Toutes les valeurs string sont sécurisées contre les injections XSS
```

### Nettoyage manuel

```php
$cleanValue = Validator::sanitize($userInput);
// Équivalent à: htmlspecialchars(trim($userInput), ENT_QUOTES, 'UTF-8')
```

## Bonnes pratiques

1. **Toujours valider côté serveur** : Ne jamais faire confiance aux validations JavaScript uniquement
2. **Valider tôt** : Valider dès la réception des données POST
3. **Messages clairs** : Utiliser des labels explicites pour les messages d'erreur
4. **Chaînage** : Utiliser le chaînage de méthodes pour une meilleure lisibilité
5. **Données nettoyées** : Toujours utiliser `getValidatedData()` pour récupérer les données sécurisées
6. **Validations conditionnelles** : Utiliser `if` pour valider uniquement si le champ est présent
7. **Validation métier** : Ajouter des validations personnalisées avec `custom()` ou `addError()`

## Intégration avec les formulaires existants

Tous les contrôleurs suivants ont été mis à jour avec la validation :

- ✅ `AuthController` : Inscription, connexion, récupération mot de passe
- ✅ `ProprietaireController` : Création/modification annonces, profil
- ✅ `EtudiantController` : Mise à jour profil

## Compatibilité

- PHP 7.4+
- Compatible avec tous les formulaires de l'application
- Fonctionne avec les données POST et les fichiers uploadés
- Supporte l'UTF-8 et les caractères accentués
