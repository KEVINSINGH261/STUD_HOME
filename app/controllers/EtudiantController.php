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
        $favoris = $favoriModel->findByEtudiant($this->getUserId());
        
        $this->view('etudiant/favoris', [
            'favoris' => $favoris,
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
        $etudiantModel->updateEcole($this->getUserId(), $ecole);
        
        $this->setFlash('success', 'Profil mis à jour avec succès.');
        $this->redirect('etudiant/profile');
    }
}
