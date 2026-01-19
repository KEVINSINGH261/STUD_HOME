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
        
        $this->view('proprietaire/dashboard', [
            'proprietaire' => $proprietaire,
            'annoncesCount' => $annoncesCount,
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
        
        $titre = $this->post('titre');
        $description = $this->post('description');
        $type = $this->post('type');
        $adresse = $this->post('adresse');
        $ville = $this->post('ville');
        $codePostal = $this->post('code_postal');
        $prix = $this->post('prix');
        $surface = $this->post('surface');
        $chambres = $this->post('chambres');
        
        // Validation
        if (empty($titre) || empty($description) || empty($type) || empty($ville) || empty($prix)) {
            $this->setFlash('error', 'Veuillez remplir tous les champs obligatoires.');
            $this->redirect('proprietaire/annonces/create');
            return;
        }
        
        // Gestion de l'upload de photo
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->uploadPhoto($_FILES['photo']);
            
            if (!$photoPath) {
                $this->setFlash('error', 'Erreur lors de l\'upload de la photo.');
                $this->redirect('proprietaire/annonces/create');
                return;
            }
        }
        
        // Création de l'annonce
        $annonceModel = $this->model('Annonce');
        $annonceId = $annonceModel->create([
            'proprietaire_id' => $this->getUserId(),
            'titre' => $titre,
            'description' => $description,
            'type' => $type,
            'adresse' => $adresse,
            'ville' => $ville,
            'code_postal' => $codePostal,
            'prix' => $prix,
            'surface' => $surface,
            'nombre_chambres' => $chambres,
            'photo' => $photoPath
        ]);
        
        if ($annonceId) {
            $this->setFlash('success', 'Annonce créée avec succès.');
            $this->redirect('proprietaire/annonces');
        } else {
            $this->setFlash('error', 'Erreur lors de la création de l\'annonce.');
            $this->redirect('proprietaire/annonces/create');
        }
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
        
        $data = [
            'titre' => $this->post('titre'),
            'description' => $this->post('description'),
            'type' => $this->post('type'),
            'adresse' => $this->post('adresse'),
            'ville' => $this->post('ville'),
            'code_postal' => $this->post('code_postal'),
            'prix' => $this->post('prix'),
            'surface' => $this->post('surface'),
            'nombre_chambres' => $this->post('chambres')
        ];
        
        // Gestion de la nouvelle photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->uploadPhoto($_FILES['photo']);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
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
}
