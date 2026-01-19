<?php
/**
 * Contrôleur AdminController - Backoffice d'administration
 */
class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
    }
    
    /**
     * Dashboard administrateur
     */
    public function dashboard(): void
    {
        $etudiantModel = $this->model('Etudiant');
        $proprietaireModel = $this->model('Proprietaire');
        $annonceModel = $this->model('Annonce');
        
        $stats = [
            'total_etudiants' => $etudiantModel->countAll(),
            'total_proprietaires' => $proprietaireModel->countAll(),
            'total_annonces' => $annonceModel->countByStatut('active'),
            'annonces_inactives' => $annonceModel->countByStatut('inactive')
        ];
        
        $this->view('admin/dashboard', [
            'stats' => $stats,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Liste des utilisateurs
     */
    public function utilisateurs(): void
    {
        $etudiantModel = $this->model('Etudiant');
        $proprietaireModel = $this->model('Proprietaire');
        
        $etudiants = $etudiantModel->findAllWithUsers();
        $proprietaires = $proprietaireModel->findAllWithUsers();
        
        $this->view('admin/utilisateurs', [
            'etudiants' => $etudiants,
            'proprietaires' => $proprietaires,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Supprime un utilisateur
     */
    public function deleteUtilisateur(int $id): void
    {
        $utilisateurModel = $this->model('Utilisateur');
        $result = $utilisateurModel->delete($id);
        
        if ($result) {
            $this->setFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression.');
        }
        
        $this->redirect('admin/utilisateurs');
    }
    
    /**
     * Liste des annonces
     */
    public function annonces(): void
    {
        $annonceModel = $this->model('Annonce');
        $annonces = $annonceModel->findAllWithProprietaire();
        
        $this->view('admin/annonces', [
            'annonces' => $annonces,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Valide une annonce
     */
    public function validateAnnonce(int $id): void
    {
        $annonceModel = $this->model('Annonce');
        $result = $annonceModel->updateStatut($id, 'active');
        
        if ($result) {
            $this->setFlash('success', 'Annonce validée.');
        } else {
            $this->setFlash('error', 'Erreur lors de la validation.');
        }
        
        $this->redirect('admin/annonces');
    }
    
    /**
     * Supprime une annonce
     */
    public function deleteAnnonce(int $id): void
    {
        $favoriModel = $this->model('Favori');
        $favoriModel->deleteByAnnonce($id);
        
        $annonceModel = $this->model('Annonce');
        $result = $annonceModel->delete($id);
        
        if ($result) {
            $this->setFlash('success', 'Annonce supprimée avec succès.');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression.');
        }
        
        $this->redirect('admin/annonces');
    }
    
    /**
     * Statistiques
     */
    public function statistics(): void
    {
        $etudiantModel = $this->model('Etudiant');
        $proprietaireModel = $this->model('Proprietaire');
        $annonceModel = $this->model('Annonce');
        
        $stats = [
            'total_etudiants' => $etudiantModel->countAll(),
            'total_proprietaires' => $proprietaireModel->countAll(),
            'total_annonces_active' => $annonceModel->countByStatut('active'),
            'total_annonces_inactive' => $annonceModel->countByStatut('inactive')
        ];
        
        $this->view('admin/statistics', [
            'stats' => $stats,
            'flash' => $this->getFlash()
        ]);
    }
}
