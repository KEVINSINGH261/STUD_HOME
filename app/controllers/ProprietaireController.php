<?php
/**
 * Contrôleur ProprietaireController - Espace propriétaire
 */
class ProprietaireController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('proprietaire');
    }
    
    /**
     * Dashboard propriétaire
     */
    public function dashboard(): void
    {
        $proprietaireModel = $this->model('Proprietaire');
        $proprietaire = $proprietaireModel->findByUserId($this->getUserId());
        
        $annonceModel = $this->model('Annonce');
        $annoncesCount = $annonceModel->count(['proprietaire_id' => $this->getUserId()]);
        
        // Compter les demandes d'intérêt non vues
        $demandeModel = $this->model('DemandeInteret');
        $demandesCount = $demandeModel->countByStatutForProprietaire($this->getUserId(), 'nouveau');
        
        $this->view('proprietaire/dashboard', [
            'proprietaire' => $proprietaire,
            'annoncesCount' => $annoncesCount,
            'demandesCount' => $demandesCount,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Liste des annonces du propriétaire
     */
    public function mesAnnonces(): void
    {
        $annonceModel = $this->model('Annonce');
        $annonces = $annonceModel->findByProprietaire($this->getUserId());
        
        // Ajouter l'image principale à chaque annonce
        foreach ($annonces as &$annonce) {
            $annonce['image_principale'] = $annonceModel->getImagePrincipale($annonce['id']);
        }
        
        $this->view('proprietaire/annonces', [
            'annonces' => $annonces,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Affiche le formulaire de création d'annonce
     */
    public function createAnnonce(): void
    {
        $this->view('proprietaire/create-annonce', [
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Enregistre une nouvelle annonce
     */
    public function storeAnnonce(): void
    {
        if (!$this->isPost()) {
            $this->redirect('proprietaire/annonces/create');
            return;
        }
        
        // Récupération des données
        $postData = [
            'titre' => $this->post('titre'),
            'description' => $this->post('description'),
            'type' => $this->post('type'),
            'adresse' => $this->post('adresse'),
            'ville' => $this->post('ville'),
            'code_postal' => $this->post('code_postal'),
            'prix' => $this->post('prix'),
            'surface' => $this->post('surface'),
            'chambres' => $this->post('chambres'),
            'statut' => $this->post('statut', 'active')
        ];
        
        // Validation complète
        $validator = new Validator($postData);
        
        $validator->required('titre', 'Titre')
                  ->minLength('titre', 5, 'Titre')
                  ->maxLength('titre', 200, 'Titre')
                  ->regex('titre', '/^[a-zA-ZÀ-ÿ0-9\s\'-]+$/u', 'Titre ne peut contenir que des lettres, chiffres, espaces, tirets et apostrophes')
                  
                  ->required('description', 'Description')
                  ->minLength('description', 20, 'Description')
                  ->maxLength('description', 5000, 'Description')
                  
                  ->required('type', 'Type de logement')
                  ->in('type', ['Studio', 'T1', 'T2', 'T3', 'T4', 'Maison', 'Colocation'], 'Type de logement')
                  
                  ->required('ville', 'Ville')
                  ->minLength('ville', 2, 'Ville')
                  ->maxLength('ville', 100, 'Ville')
                  ->regex('ville', '/^[a-zA-ZÀ-ÿ\s\'-]+$/u', 'Ville ne peut contenir que des lettres, espaces, tirets et apostrophes')
                  
                  ->required('prix', 'Prix')
                  ->numeric('prix', 'Prix')
                  ->min('prix', 1, 'Prix')
                  ->max('prix', 10000, 'Prix');
        
        // Validations optionnelles
        if (!empty($postData['adresse'])) {
            $validator->maxLength('adresse', 255, 'Adresse')
                      ->regex('adresse', '/^[a-zA-ZÀ-ÿ0-9\s\'-]+$/u', 'Adresse ne peut contenir que des lettres, chiffres, espaces, tirets et apostrophes');
        }
        
        if (!empty($postData['code_postal'])) {
            $validator->postalCode('code_postal');
        }
        
        if (!empty($postData['surface'])) {
            $validator->integer('surface', 'Surface')
                      ->min('surface', 1, 'Surface')
                      ->max('surface', 1000, 'Surface');
        }
        
        if (!empty($postData['chambres'])) {
            $validator->integer('chambres', 'Nombre de chambres')
                      ->min('chambres', 0, 'Nombre de chambres')
                      ->max('chambres', 20, 'Nombre de chambres');
        }
        
        // Validation du statut
        $validator->in('statut', ['active', 'inactive'], 'Statut');
        
        // Si erreurs, retour au formulaire
        if (!$validator->isValid()) {
            $this->setFlash('error', $validator->getErrorsAsString());
            $this->redirect('proprietaire/annonces/create');
            return;
        }
        
        // Récupération des données nettoyées
        $validatedData = $validator->getValidatedData();
        
        // Création de l'annonce avec les données validées
        $annonceModel = $this->model('Annonce');
        $annonceId = $annonceModel->create([
            'proprietaire_id' => $this->getUserId(),
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'type' => $validatedData['type'],
            'adresse' => $validatedData['adresse'],
            'ville' => $validatedData['ville'],
            'code_postal' => $validatedData['code_postal'],
            'prix' => $validatedData['prix'],
            'surface' => $validatedData['surface'],
            'nombre_chambres' => $validatedData['chambres'],
            'statut' => $validatedData['statut']
        ]);
        
        if (!$annonceId) {
            $this->setFlash('error', 'Erreur lors de la création de l\'annonce.');
            $this->redirect('proprietaire/annonces/create');
            return;
        }
        
        // Gestion des uploads multiples d'images
        if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
            $uploadedCount = $this->uploadMultiplePhotos($_FILES['photos'], $annonceId);
            
            if ($uploadedCount === 0) {
                $this->setFlash('warning', 'Annonce créée mais aucune image n\'a pu être uploadée. Vérifiez le format et la taille.');
            } else {
                $this->setFlash('success', 'Annonce créée avec succès. ' . $uploadedCount . ' image(s) uploadée(s).');
            }
        } else {
            $this->setFlash('success', 'Annonce créée avec succès (sans image).');
        }
        
        $this->redirect('proprietaire/annonces');
    }
    
    /**
     * Affiche le formulaire d'édition d'annonce
     */
    public function editAnnonce(int $id): void
    {
        $annonceModel = $this->model('Annonce');
        $annonce = $annonceModel->findById($id);
        
        if (!$annonce || !$annonceModel->belongsTo($id, $this->getUserId())) {
            $this->setFlash('error', 'Annonce introuvable.');
            $this->redirect('proprietaire/annonces');
            return;
        }
        
        $this->view('proprietaire/edit-annonce', [
            'annonce' => $annonce,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Met à jour une annonce
     */
    public function updateAnnonce(int $id): void
    {
        if (!$this->isPost()) {
            $this->redirect('proprietaire/annonces/edit/' . $id);
            return;
        }
        
        $annonceModel = $this->model('Annonce');
        
        // Vérifier que l'annonce appartient au propriétaire
        if (!$annonceModel->belongsTo($id, $this->getUserId())) {
            $this->setFlash('error', 'Action non autorisée.');
            $this->redirect('proprietaire/annonces');
            return;
        }
        
        $postData = [
            'titre' => $this->post('titre'),
            'description' => $this->post('description'),
            'type' => $this->post('type'),
            'adresse' => $this->post('adresse'),
            'ville' => $this->post('ville'),
            'code_postal' => $this->post('code_postal'),
            'prix' => $this->post('prix'),
            'surface' => $this->post('surface'),
            'chambres' => $this->post('chambres')
        ];
        
        // Validation complète
        $validator = new Validator($postData);
        
        $validator->required('titre', 'Titre')
                  ->minLength('titre', 5, 'Titre')
                  ->maxLength('titre', 200, 'Titre')
                  
                  ->required('description', 'Description')
                  ->minLength('description', 20, 'Description')
                  ->maxLength('description', 5000, 'Description')
                  
                  ->required('type', 'Type de logement')
                  ->in('type', ['Studio', 'T1', 'T2', 'T3', 'T4', 'Maison', 'Colocation'], 'Type de logement')
                  
                  ->required('ville', 'Ville')
                  ->minLength('ville', 2, 'Ville')
                  ->maxLength('ville', 100, 'Ville')
                  
                  ->required('prix', 'Prix')
                  ->numeric('prix', 'Prix')
                  ->min('prix', 1, 'Prix')
                  ->max('prix', 10000, 'Prix');
        
        // Validations optionnelles
        if (!empty($postData['adresse'])) {
            $validator->maxLength('adresse', 255, 'Adresse');
        }
        
        if (!empty($postData['code_postal'])) {
            $validator->postalCode('code_postal');
        }
        
        if (!empty($postData['surface'])) {
            $validator->integer('surface', 'Surface')
                      ->min('surface', 1, 'Surface')
                      ->max('surface', 1000, 'Surface');
        }
        
        if (!empty($postData['chambres'])) {
            $validator->integer('chambres', 'Nombre de chambres')
                      ->min('chambres', 0, 'Nombre de chambres')
                      ->max('chambres', 20, 'Nombre de chambres');
        }
        
        // Si erreurs, retour au formulaire
        if (!$validator->isValid()) {
            $this->setFlash('error', $validator->getErrorsAsString());
            $this->redirect('proprietaire/annonces/edit/' . $id);
            return;
        }
        
        // Données validées et nettoyées
        $validatedData = $validator->getValidatedData();
        
        $data = [
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'type' => $validatedData['type'],
            'adresse' => $validatedData['adresse'],
            'ville' => $validatedData['ville'],
            'code_postal' => $validatedData['code_postal'],
            'prix' => $validatedData['prix'],
            'surface' => $validatedData['surface'],
            'nombre_chambres' => $validatedData['chambres']
        ];
        
        // Gestion des nouvelles photos
        if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
            $this->uploadMultiplePhotos($_FILES['photos'], $id);
        }
        
        $result = $annonceModel->update($id, $data);
        
        if ($result) {
            $this->setFlash('success', 'Annonce mise à jour avec succès.');
        } else {
            $this->setFlash('error', 'Erreur lors de la mise à jour.');
        }
        
        $this->redirect('proprietaire/annonces');
    }
    
    /**
     * Supprime une annonce
     */
    public function deleteAnnonce(int $id): void
    {
        $annonceModel = $this->model('Annonce');
        
        // Vérifier que l'annonce appartient au propriétaire
        if (!$annonceModel->belongsTo($id, $this->getUserId())) {
            $this->setFlash('error', 'Action non autorisée.');
            $this->redirect('proprietaire/annonces');
            return;
        }
        
        // Supprimer les favoris associés
        $favoriModel = $this->model('Favori');
        $favoriModel->deleteByAnnonce($id);
        
        // Supprimer l'annonce
        $result = $annonceModel->delete($id);
        
        if ($result) {
            $this->setFlash('success', 'Annonce supprimée avec succès.');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression.');
        }
        
        $this->redirect('proprietaire/annonces');
    }
    
    /**
     * Affiche le profil
     */
    public function profile(): void
    {
        $proprietaireModel = $this->model('Proprietaire');
        $proprietaire = $proprietaireModel->findByUserId($this->getUserId());
        
        $this->view('proprietaire/profile', [
            'proprietaire' => $proprietaire,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Met à jour le profil
     */
    public function updateProfile(): void
    {
        if (!$this->isPost()) {
            $this->redirect('proprietaire/profile');
            return;
        }
        
        // Récupération des données
        $postData = [
            'nom' => $this->post('nom'),
            'prenom' => $this->post('prenom'),
            'email' => $this->post('email'),
            'telephone' => $this->post('telephone')
        ];
        
        // Validation complète
        $validator = new Validator($postData);
        
        $validator->required('nom', 'Nom')
                  ->minLength('nom', 2, 'Nom')
                  ->maxLength('nom', 50, 'Nom')
                  ->regex('nom', '/^[a-zA-ZÀ-ÿ\s\-]+$/', 'Le nom ne doit contenir que des lettres, espaces et tirets.')
                  
                  ->required('prenom', 'Prénom')
                  ->minLength('prenom', 2, 'Prénom')
                  ->maxLength('prenom', 50, 'Prénom')
                  ->regex('prenom', '/^[a-zA-ZÀ-ÿ\s\-]+$/', 'Le prénom ne doit contenir que des lettres, espaces et tirets.')
                  
                  ->required('email', 'Email')
                  ->email('email')
                  
                  ->required('telephone', 'Téléphone')
                  ->phone('telephone');
        
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $utilisateurModel = $this->model('Utilisateur');
        $existingUser = $utilisateurModel->findByEmail($postData['email']);
        if ($existingUser && $existingUser['id'] != $this->getUserId()) {
            $validator->addError('email', 'Cet email est déjà utilisé par un autre compte.');
        }
        
        // Si erreurs, retour au formulaire
        if (!$validator->isValid()) {
            $this->setFlash('error', $validator->getErrorsAsString());
            $this->redirect('proprietaire/profile');
            return;
        }
        
        // Données validées
        $validatedData = $validator->getValidatedData();
        $nom = $validatedData['nom'];
        $prenom = $validatedData['prenom'];
        $email = $validatedData['email'];
        $nom = $this->post('nom');
        $prenom = $this->post('prenom');
        $email = $this->post('email');
        $telephone = $this->post('telephone');
        $password = $this->post('password');
        
        // Mise à jour de l'utilisateur
        $utilisateurModel = $this->model('Utilisateur');
        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ];
        
        if (!empty($password)) {
            $data['mot_de_passe'] = $password;
        }
        
        $utilisateurModel->updateProfile($this->getUserId(), $data);
        
        // Mise à jour du téléphone
        $proprietaireModel = $this->model('Proprietaire');
        $proprietaireModel->updateTelephone($this->getUserId(), $telephone);
        
        $this->setFlash('success', 'Profil mis à jour avec succès.');
        $this->redirect('proprietaire/profile');
    }
    
    /**
     * Upload d'une photo d'annonce
     */
    private function uploadPhoto(array $file): ?string
    {
        // Vérifier le type de fichier
        if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
            return null;
        }
        
        // Vérifier la taille
        if ($file['size'] > MAX_FILE_SIZE) {
            return null;
        }
        
        // Générer un nom unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('annonce_', true) . '.' . $extension;
        $destination = UPLOAD_PATH . '/annonces/' . $filename;
        
        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'uploads/annonces/' . $filename;
        }
        
        return null;
    }
    
    /**
     * Upload de plusieurs photos pour une annonce
     */
    private function uploadMultiplePhotos(array $files, int $annonceId): int
    {
        $annonceModel = $this->model('Annonce');
        $uploadedCount = 0;
        $existingImages = $annonceModel->getImages($annonceId);
        $isFirstImage = empty($existingImages);
        
        // Créer le dossier si nécessaire
        $uploadDir = UPLOAD_PATH . '/annonces';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Log pour debug
        error_log("DEBUG Upload - Nombre de fichiers: " . count($files['name']));
        
        // Traiter chaque fichier
        for ($i = 0; $i < count($files['name']); $i++) {
            error_log("DEBUG Upload - Fichier $i - Nom: " . $files['name'][$i] . ", Erreur: " . $files['error'][$i] . ", Type: " . $files['type'][$i]);
            
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                error_log("DEBUG Upload - Erreur upload pour fichier $i: " . $files['error'][$i]);
                continue;
            }
            
            // Vérifier le type
            if (!in_array($files['type'][$i], ALLOWED_IMAGE_TYPES)) {
                error_log("DEBUG Upload - Type non autorisé: " . $files['type'][$i]);
                continue;
            }
            
            // Vérifier la taille
            if ($files['size'][$i] > MAX_FILE_SIZE) {
                error_log("DEBUG Upload - Fichier trop volumineux: " . $files['size'][$i]);
                continue;
            }
            
            // Générer un nom unique
            $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $filename = uniqid('annonce_', true) . '.' . $extension;
            $destination = $uploadDir . '/' . $filename;
            
            error_log("DEBUG Upload - Tentative de déplacement vers: " . $destination);
            
            // Déplacer le fichier
            if (move_uploaded_file($files['tmp_name'][$i], $destination)) {
                $chemin = 'uploads/annonces/' . $filename;
                $estPrincipale = ($isFirstImage && $uploadedCount === 0);
                
                error_log("DEBUG Upload - Fichier déplacé avec succès: $chemin, Principale: " . ($estPrincipale ? 'oui' : 'non'));
                
                // Ajouter l'image dans la BD
                $addResult = $annonceModel->addImage($annonceId, $chemin, $estPrincipale, $uploadedCount);
                
                if ($addResult) {
                    error_log("DEBUG Upload - Image enregistrée dans BD avec succès");
                    $uploadedCount++;
                } else {
                    error_log("ERREUR Upload - Échec enregistrement dans BD pour: $chemin");
                }
            } else {
                error_log("DEBUG Upload - Échec du déplacement du fichier $i");
            }
        }
        
        error_log("DEBUG Upload - Total uploadé: $uploadedCount");
        
        return $uploadedCount;
    }
    
    /**
     * Supprime une image d'annonce
     */
    public function deleteImage(int $imageId): void
    {
        $annonceModel = $this->model('Annonce');
        
        // Récupérer l'image pour vérifier qu'elle appartient au propriétaire
        $sql = "SELECT ai.*, a.proprietaire_id 
                FROM annonces_images ai
                INNER JOIN annonces a ON ai.annonce_id = a.id
                WHERE ai.id = :id";
        
        $image = $annonceModel->queryOne($sql, ['id' => $imageId]);
        
        if (!$image || $image['proprietaire_id'] != $this->getUserId()) {
            echo json_encode(['success' => false, 'message' => 'Action non autorisée']);
            return;
        }
        
        // Supprimer le fichier physique
        $filePath = PUBLIC_PATH . '/' . $image['chemin'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Supprimer de la base de données
        if ($annonceModel->deleteImage($imageId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
    
    /**
     * Définit une image comme principale
     */
    public function setMainImage(int $imageId): void
    {
        $annonceModel = $this->model('Annonce');
        
        // Récupérer l'image pour vérifier qu'elle appartient au propriétaire
        $sql = "SELECT ai.*, a.proprietaire_id, ai.annonce_id
                FROM annonces_images ai
                INNER JOIN annonces a ON ai.annonce_id = a.id
                WHERE ai.id = :id";
        
        $image = $annonceModel->queryOne($sql, ['id' => $imageId]);
        
        if (!$image || $image['proprietaire_id'] != $this->getUserId()) {
            echo json_encode(['success' => false, 'message' => 'Action non autorisée']);
            return;
        }
        
        if ($annonceModel->setImagePrincipale($imageId, $image['annonce_id'])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
}
