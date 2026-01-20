<?php
/**
 * Contrôleur EtudiantController - Espace étudiant
 */
class EtudiantController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('etudiant');
    }
    
    /**
     * Dashboard étudiant
     */
    public function dashboard(): void
    {
        $etudiantModel = $this->model('Etudiant');
        $etudiant = $etudiantModel->findByUserId($this->getUserId());
        
        $favoriModel = $this->model('Favori');
        $favorisCount = $favoriModel->countByEtudiant($this->getUserId());
        
        $this->view('etudiant/dashboard', [
            'etudiant' => $etudiant,
            'favorisCount' => $favorisCount,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Liste des favoris
     */
    public function favoris(): void
    {
        $favoriModel = $this->model('Favori');
        
        // Pagination
        $perPage = 9;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;
        
        // Récupérer les favoris paginés
        $favoris = $favoriModel->findByEtudiantPaginated($this->getUserId(), $perPage, $offset);
        $totalFavoris = $favoriModel->countByEtudiant($this->getUserId());
        $totalPages = ceil($totalFavoris / $perPage);
        
        // Ajouter l'image principale à chaque favori
        $annonceModel = $this->model('Annonce');
        foreach ($favoris as &$favori) {
            $favori['image_principale'] = $annonceModel->getImagePrincipale($favori['annonce_id']);
        }
        
        $this->view('etudiant/favoris', [
            'favoris' => $favoris,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Ajoute une annonce aux favoris
     */
    public function addFavori(int $annonceId): void
    {
        $favoriModel = $this->model('Favori');
        $result = $favoriModel->add($this->getUserId(), $annonceId);
        
        if ($result > 0) {
            $this->setFlash('success', 'Annonce ajoutée aux favoris.');
        } else {
            $this->setFlash('info', 'Cette annonce est déjà dans vos favoris.');
        }
        
        $this->redirect('annonces/details/' . $annonceId);
    }
    
    /**
     * Retire une annonce des favoris
     */
    public function removeFavori(int $annonceId): void
    {
        $favoriModel = $this->model('Favori');
        $result = $favoriModel->remove($this->getUserId(), $annonceId);
        
        if ($result) {
            $this->setFlash('success', 'Annonce retirée des favoris.');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression.');
        }
        
        // Rediriger vers la page précédente ou les favoris
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/etudiant/favoris';
        header("Location: " . $referer);
        exit;
    }
    
    /**
     * Affiche le profil
     */
    public function profile(): void
    {
        $etudiantModel = $this->model('Etudiant');
        $etudiant = $etudiantModel->findByUserId($this->getUserId());
        
        $this->view('etudiant/profile', [
            'etudiant' => $etudiant,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Met à jour le profil
     */
    public function updateProfile(): void
    {
        if (!$this->isPost()) {
            $this->redirect('etudiant/profile');
            return;
        }
        
        // Récupération des données
        $postData = [
            'nom' => $this->post('nom'),
            'prenom' => $this->post('prenom'),
            'email' => $this->post('email'),
            'ecole' => $this->post('ecole'),
            'password' => $this->post('password')
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
                  
                  ->required('ecole', 'École')
                  ->minLength('ecole', 2, 'École')
                  ->maxLength('ecole', 100, 'École');
        
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $utilisateurModel = $this->model('Utilisateur');
        $existingUser = $utilisateurModel->findByEmail($postData['email']);
        if ($existingUser && $existingUser['id'] != $this->getUserId()) {
            $validator->addError('email', 'Cet email est déjà utilisé par un autre compte.');
        }
        
        // Validation du mot de passe (si fourni)
        if (!empty($postData['password'])) {
            $passwordValidation = $utilisateurModel->validatePassword($postData['password']);
            if (!$passwordValidation['valid']) {
                foreach ($passwordValidation['errors'] as $error) {
                    $validator->addError('password', $error);
                }
            }
        }
        
        // Si erreurs, retour au formulaire
        if (!$validator->isValid()) {
            $this->setFlash('error', $validator->getErrorsAsString());
            $this->redirect('etudiant/profile');
            return;
        }
        
        // Données validées
        $validatedData = $validator->getValidatedData();
        
        // Mise à jour de l'utilisateur
        $data = [
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email']
        ];
        
        if (!empty($validatedData['password'])) {
            $data['mot_de_passe'] = $validatedData['password'];
        $nom = $this->post('nom');
        $prenom = $this->post('prenom');
        $email = $this->post('email');
        $ecole = $this->post('ecole');
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
        
        // Mise à jour de l'école
        $etudiantModel = $this->model('Etudiant');
        $etudiantModel->updateEcole($this->getUserId(), $validatedData['ecole']);
        $etudiantModel->updateEcole($this->getUserId(), $ecole);
        
        $this->setFlash('success', 'Profil mis à jour avec succès.');
        $this->redirect('etudiant/profile');
    }
}
