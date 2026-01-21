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
        $signalementModel = $this->model('Signalement');
        
        $stats = [
            'total_etudiants' => $etudiantModel->countAll(),
            'total_proprietaires' => $proprietaireModel->countAll(),
            'total_annonces' => $annonceModel->countByStatut('active'),
            'annonces_inactives' => $annonceModel->countByStatut('inactive'),
            'signalements_nouveaux' => $signalementModel->countByStatut('nouveau')
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
        
        // Fusionner les deux tableaux en un seul avec le bon format
        $users = [];
        
        // Ajouter les étudiants
        if (!empty($etudiants)) {
            foreach ($etudiants as $etudiant) {
                $users[] = [
                    'id' => $etudiant['id'],
                    'type' => 'etudiant',
                    'nom' => $etudiant['nom'],
                    'prenom' => $etudiant['prenom'],
                    'email' => $etudiant['email'],
                    'ecole' => $etudiant['ecole'] ?? null,
                    'telephone' => null,
                    'created_at' => $etudiant['date_inscription'] ?? $etudiant['created_at'] ?? date('Y-m-d')
                ];
            }
        }
        
        // Ajouter les propriétaires
        if (!empty($proprietaires)) {
            foreach ($proprietaires as $proprietaire) {
                $users[] = [
                    'id' => $proprietaire['id'],
                    'type' => 'proprietaire',
                    'nom' => $proprietaire['nom'],
                    'prenom' => $proprietaire['prenom'],
                    'email' => $proprietaire['email'],
                    'ecole' => null,
                    'telephone' => $proprietaire['telephone'] ?? null,
                    'created_at' => $proprietaire['date_inscription'] ?? $proprietaire['created_at'] ?? date('Y-m-d')
                ];
            }
        }
        
        // Trier par date d'inscription (plus récent en premier)
        if (!empty($users)) {
            usort($users, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }
        
        $this->view('admin/utilisateurs', [
            'users' => $users,
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
    
    /**
     * Liste des signalements
     */
    public function signalements(): void
    {
        $signalementModel = $this->model('Signalement');
        $statut = $this->get('statut') ?? 'nouveau';
        
        if ($statut === 'tous') {
            $signalements = $signalementModel->getAll();
        } else {
            $signalements = $signalementModel->getByStatut($statut);
        }
        
        $counts = [
            'nouveau' => $signalementModel->countByStatut('nouveau'),
            'en_cours' => $signalementModel->countByStatut('en_cours'),
            'resolu' => $signalementModel->countByStatut('resolu'),
            'clos' => $signalementModel->countByStatut('clos')
        ];
        
        $this->view('admin/signalements', [
            'signalements' => $signalements,
            'counts' => $counts,
            'current_statut' => $statut,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Met à jour le statut d'un signalement
     */
    public function updateSignalementStatut(int $id): void
    {
        $nouveau_statut = $this->post('statut');
        
        if (!$nouveau_statut || !in_array($nouveau_statut, ['nouveau', 'en_cours', 'resolu', 'clos'])) {
            $this->setFlash('error', 'Statut invalide.');
            $this->redirect('admin/signalements');
            return;
        }
        
        $signalementModel = $this->model('Signalement');
        
        if ($signalementModel->updateStatut($id, $nouveau_statut)) {
            $this->setFlash('success', 'Statut du signalement mis à jour.');
        } else {
            $this->setFlash('error', 'Erreur lors de la mise à jour.');
        }
        
        $this->redirect('admin/signalements');
    }
}
