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
     * Suppression d'un utilisateur
     */
    public function deleteUtilisateur($id)
    {
        // Vérifier que c'est une requête POST
        if (!$this->isPost()) {
            $this->redirect('admin/utilisateurs');
            return;
        }
        
        // Vérifier que l'utilisateur est admin
        if (!$this->isAuthenticated() || $this->getUserRole() !== 'admin') {
            $this->setFlash('error', 'Accès refusé.');
            $this->redirect('login');
            return;
        }
        
        $userId = (int)$id;
        
        try {
            // Empêcher la suppression de son propre compte
            if ($userId === $this->getUserId()) {
                $this->setFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
                $this->redirect('admin/utilisateurs');
                return;
            }
            
            // Charger le modèle Utilisateur
            $userModel = $this->model('Utilisateur');
            
            // Vérifier que l'utilisateur existe
            $user = $userModel->findById($userId);
            if (!$user) {
                $this->setFlash('error', 'Utilisateur introuvable.');
                $this->redirect('admin/utilisateurs');
                return;
            }
            
            // Préparer les informations pour l'email
            $userEmail = $user['email'];
            $userName = $user['prenom'] . ' ' . $user['nom'];
            
            // Utiliser PDO directement pour la suppression en cascade
            $db = $this->getDb();
            $db->beginTransaction();
            
            try {
                // Supprimer selon le type d'utilisateur
                if ($user['type'] === 'etudiant') {
                    // Supprimer les favoris de l'étudiant (l'ID est le même)
                    $stmt = $db->prepare("DELETE FROM favoris WHERE etudiant_id = ?");
                    $stmt->execute([$userId]);
                    
                    // Supprimer l'étudiant
                    $stmt = $db->prepare("DELETE FROM etudiants WHERE id = ?");
                    $stmt->execute([$userId]);
                    
                } elseif ($user['type'] === 'proprietaire') {
                    // Récupérer les annonces du propriétaire (l'ID est le même)
                    $stmt = $db->prepare("SELECT id FROM annonces WHERE proprietaire_id = ?");
                    $stmt->execute([$userId]);
                    $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Supprimer les favoris liés aux annonces
                    if (!empty($annonces)) {
                        foreach ($annonces as $annonce) {
                            $stmt = $db->prepare("DELETE FROM favoris WHERE annonce_id = ?");
                            $stmt->execute([$annonce['id']]);
                        }
                    }
                    
                    // Supprimer les annonces
                    $stmt = $db->prepare("DELETE FROM annonces WHERE proprietaire_id = ?");
                    $stmt->execute([$userId]);
                    
                    // Supprimer le propriétaire
                    $stmt = $db->prepare("DELETE FROM proprietaires WHERE id = ?");
                    $stmt->execute([$userId]);
                }
                
                // Supprimer l'utilisateur
                $stmt = $db->prepare("DELETE FROM utilisateurs WHERE id = ?");
                $stmt->execute([$userId]);
                
                $db->commit();
                
                // Envoyer l'email de suppression après la suppression réussie
                $emailService = new EmailService();
                $emailService->sendAccountDeletionEmail($userEmail, $userName);
                
                $this->setFlash('success', 'Utilisateur supprimé avec succès.');
                
            } catch (Exception $e) {
                $db->rollBack();
                error_log("Erreur lors de la suppression: " . $e->getMessage());
                $this->setFlash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
            }
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur : ' . $e->getMessage());
        }
        
        $this->redirect('admin/utilisateurs');
    }
    
    /**
     * Méthode helper pour récupérer la connexion DB
     */
    
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