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
     * Ajoute un utilisateur (depuis le backoffice admin)
     */
    public function addUtilisateur(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/utilisateurs');
            return;
        }
        
        $type = trim($this->post('type'));
        $nom = trim($this->post('nom'));
        $prenom = trim($this->post('prenom'));
        $email = trim($this->post('email'));
        $password = $this->post('password');
        $passwordConfirm = $this->post('password_confirm');
        
        $utilisateurModel = $this->model('Utilisateur');
        
        // Validation complète (même validation que l'inscription)
        $errors = [];
        
        if (empty($type) || !in_array($type, ['etudiant', 'proprietaire'])) {
            $errors[] = 'Veuillez sélectionner un type de compte.';
        }
        
        // Validation du nom
        $nomValidation = $utilisateurModel->validateName($nom, 'Nom');
        if (!$nomValidation['valid']) {
            $errors = array_merge($errors, $nomValidation['errors']);
        }
        
        // Validation du prénom
        $prenomValidation = $utilisateurModel->validateName($prenom, 'Prénom');
        if (!$prenomValidation['valid']) {
            $errors = array_merge($errors, $prenomValidation['errors']);
        }
        
        // Validation de l'email
        $emailValidation = $utilisateurModel->validateEmail($email);
        if (!$emailValidation['valid']) {
            $errors = array_merge($errors, $emailValidation['errors']);
        } else {
            // Vérifier si l'email existe déjà
            if ($utilisateurModel->emailExists($email)) {
                $errors[] = 'Cet email est déjà utilisé.';
            }
        }
        
        // Validation du mot de passe
        if (empty($password)) {
            $errors[] = 'Le mot de passe est obligatoire.';
        } else {
            $passwordValidation = $utilisateurModel->validatePassword($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }
        
        // Validation de la confirmation du mot de passe
        if ($password !== $passwordConfirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        
        // Validation spécifique selon le type
        if ($type === 'etudiant') {
            $ecole = trim($this->post('ecole'));
            if (empty($ecole)) {
                $errors[] = 'L\'école est obligatoire pour les étudiants.';
            } elseif (strlen($ecole) < 2) {
                $errors[] = 'Le nom de l\'école doit contenir au moins 2 caractères.';
            } elseif (strlen($ecole) > 100) {
                $errors[] = 'Le nom de l\'école ne peut pas dépasser 100 caractères.';
            }
        } else {
            $telephone = trim($this->post('telephone'));
            $phoneValidation = $utilisateurModel->validatePhone($telephone);
            if (!$phoneValidation['valid']) {
                $errors = array_merge($errors, $phoneValidation['errors']);
            }
        }
        
        // Si erreurs, retour au formulaire
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/utilisateurs');
            return;
        }
        
        // Création de l'utilisateur
        $userId = $utilisateurModel->register([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mot_de_passe' => $password,
            'type' => $type
        ]);
        
        if ($userId) {
            // Créer le profil spécifique
            if ($type === 'etudiant') {
                $ecole = trim($this->post('ecole'));
                $etudiantModel = $this->model('Etudiant');
                $etudiantModel->createProfile($userId, $ecole);
                
            } else {
                $telephone = trim($this->post('telephone'));
                $proprietaireModel = $this->model('Proprietaire');
                $proprietaireModel->createProfile($userId, $telephone);
            }
            
            $this->setFlash('success', 'Utilisateur ajouté avec succès !');
        } else {
            $this->setFlash('error', 'Erreur lors de la création de l\'utilisateur.');
        }
        
        $this->redirect('admin/utilisateurs');
    }
    
    /**
     * Supprime un utilisateur
     */
    public function deleteUtilisateur(int $id): void
    {
        $utilisateurModel = $this->model('Utilisateur');
        
        // Récupérer les informations de l'utilisateur avant suppression
        $utilisateur = $utilisateurModel->findById($id);
        
        if (!$utilisateur) {
            $this->setFlash('error', 'Utilisateur introuvable.');
            $this->redirect('admin/utilisateurs');
            return;
        }
        
        // Supprimer l'utilisateur
        $result = $utilisateurModel->delete($id);
        
        if ($result) {
            // Envoyer l'email de notification de suppression
            require_once __DIR__ . '/../services/EmailService.php';
            $emailService = new EmailService();
            $userName = $utilisateur['prenom'] . ' ' . $utilisateur['nom'];
            $emailService->sendAccountDeletionEmail($utilisateur['email'], $userName);
            
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
