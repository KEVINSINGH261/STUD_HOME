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
        $nom = trim($this->post('nom'));
        $prenom = trim($this->post('prenom'));
        $email = trim($this->post('email'));
        $ecole = trim($this->post('ecole'));
        $password = $this->post('password');

        // Validation simple
        $errors = [];
        
        if (empty($nom) || strlen($nom) < 2 || strlen($nom) > 50) {
            $errors[] = 'Nom invalide (2-50 caractères).';
        }
        
        if (empty($prenom) || strlen($prenom) < 2 || strlen($prenom) > 50) {
            $errors[] = 'Prénom invalide (2-50 caractères).';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        
        if (empty($ecole) || strlen($ecole) < 2 || strlen($ecole) > 100) {
            $errors[] = 'École invalide (2-100 caractères).';
        }

        // Vérifier si l'email existe déjà pour un autre utilisateur
        $utilisateurModel = $this->model('Utilisateur');
        $existingUser = $utilisateurModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $this->getUserId()) {
            $errors[] = 'Cet email est déjà utilisé par un autre compte.';
        }

        // Validation du mot de passe (si fourni)
        if (!empty($password)) {
            $passwordValidation = $utilisateurModel->validatePassword($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }

        // Si erreurs, retour au formulaire
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('etudiant/profile');
            return;
        }

        // Mise à jour de l'utilisateur
        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ];

        if (!empty($password)) {
            $data['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $utilisateurModel->updateProfile($this->getUserId(), $data);

        // Mise à jour de l'école
        $etudiantModel = $this->model('Etudiant');
        $etudiantModel->updateEcole($this->getUserId(), $ecole);

        $this->setFlash('success', 'Profil mis à jour avec succès.');
        $this->redirect('etudiant/profile');
    }
    
    /**
     * Affiche la page des paramètres
     */
    public function settings(): void
    {
        $utilisateurModel = $this->model('Utilisateur');
        $user = $utilisateurModel->findById($this->getUserId());
        
        $etudiantModel = $this->model('Etudiant');
        $etudiant = $etudiantModel->findByUserId($this->getUserId());
        
        $this->view('etudiant/settings', [
            'user' => $user,
            'etudiant' => $etudiant,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Met à jour les paramètres du compte
     */
    public function updateSettings(): void
    {
        if (!$this->isPost()) {
            $this->redirect('etudiant/settings');
            return;
        }

        $utilisateurModel = $this->model('Utilisateur');
        $errors = [];
        
        // Changement de mot de passe
        $currentPassword = $this->post('current_password');
        $newPassword = $this->post('new_password');
        $confirmPassword = $this->post('confirm_password');
        
        if (!empty($currentPassword) || !empty($newPassword)) {
            // Vérifier le mot de passe actuel
            $user = $utilisateurModel->findById($this->getUserId());
            
            if (!password_verify($currentPassword, $user['mot_de_passe'])) {
                $errors[] = 'Le mot de passe actuel est incorrect.';
            } elseif (empty($newPassword)) {
                $errors[] = 'Le nouveau mot de passe est requis.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'Les nouveaux mots de passe ne correspondent pas.';
            } else {
                $passwordValidation = $utilisateurModel->validatePassword($newPassword);
                if (!$passwordValidation['valid']) {
                    $errors = array_merge($errors, $passwordValidation['errors']);
                } else {
                    // Mettre à jour le mot de passe
                    $data = ['mot_de_passe' => password_hash($newPassword, PASSWORD_DEFAULT)];
                    $utilisateurModel->updateProfile($this->getUserId(), $data);
                    $this->setFlash('success', 'Mot de passe modifié avec succès.');
                }
            }
        }
        
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
        }
        
        $this->redirect('etudiant/settings');
    }
}
