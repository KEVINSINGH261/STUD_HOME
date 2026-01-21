<?php
/**
 * Contrôleur DemandeInteret
 * Gère les demandes d'intérêt des étudiants
 */
class DemandeInteretController extends Controller
{
    /**
     * Crée une nouvelle demande d'intérêt (POST)
     */
    public function create(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/');
            return;
        }

        // Vérifier l'authentification
        if (!$this->isAuthenticated()) {
            $this->setFlash('Vous devez être connecté pour soumettre une demande.', 'error');
            $this->redirect('/login');
            return;
        }

        $annonceId = (int)$this->post('annonce_id');
        $etudiantId = $this->getUserId();
        $email = $this->post('email');
        $telephone = $this->post('telephone');
        $message = $this->post('message');

        // Validation
        if (!$annonceId || !$email || !$telephone) {
            $this->setFlash('Tous les champs sont obligatoires.', 'error');
            $this->redirect("/annonces/details/{$annonceId}");
            return;
        }

        // Vérifier que l'annonce existe
        $annonceModel = $this->model('Annonce');
        $annonce = $annonceModel->findById($annonceId);
        if (!$annonce) {
            $this->setFlash('Annonce introuvable.', 'error');
            $this->redirect('/annonces');
            return;
        }

        // Vérifier qu'il n'y a pas déjà une demande active
        $demandeModel = $this->model('DemandeInteret');
        if ($demandeModel->exists($annonceId, $etudiantId)) {
            $this->setFlash('Vous avez déjà une demande active pour cette annonce.', 'error');
            $this->redirect("/annonces/details/{$annonceId}");
            return;
        }

        // Créer la demande
        try {
            $demandeModel->create([
                'annonce_id' => $annonceId,
                'etudiant_id' => $etudiantId,
                'email' => $email,
                'telephone' => $telephone,
                'message' => $message
            ]);

            // Récupérer les infos de l'étudiant et du propriétaire pour l'email
            $utilisateurModel = $this->model('Utilisateur');
            $etudiant = $utilisateurModel->findById($etudiantId);
            
            $proprietaireModel = $this->model('Proprietaire');
            $proprietaire = $proprietaireModel->findByAnnonce($annonceId);
            
            if ($proprietaire && $etudiant) {
                // Envoyer un email de notification au propriétaire
                $emailService = new EmailService();
                $messageText = "Vous avez reçu une nouvelle demande d'intérêt pour l'annonce: " . htmlspecialchars($annonce['titre']) . "\n\n";
                $messageText .= "Informations de l'étudiant:\n";
                $messageText .= "Nom: " . htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) . "\n";
                $messageText .= "Email: " . htmlspecialchars($email) . "\n";
                $messageText .= "Téléphone: " . htmlspecialchars($telephone) . "\n";
                
                if (!empty($message)) {
                    $messageText .= "\nMessage: " . htmlspecialchars($message) . "\n";
                }
                
                $messageText .= "\nConsultez vos demandes d'intérêt: " . APP_URL . "/proprietaire/demandes-interet\n";
                
                $emailService->sendCustomEmail(
                    $proprietaire['email'],
                    'Nouvelle demande d\'intérêt - ' . htmlspecialchars($annonce['titre']),
                    $messageText
                );
            }

            $this->setFlash('Votre demande a été envoyée avec succès. Le propriétaire vous répondra bientôt.', 'success');
        } catch (Exception $e) {
            $this->setFlash('Une erreur est survenue lors de l\'envoi de la demande.', 'error');
        }

        $this->redirect("/annonces/details/{$annonceId}");
    }

    /**
     * Affiche les demandes d'intérêt pour un propriétaire
     */
    public function list(): void
    {
        // Vérifier l'authentification et le rôle
        if (!$this->isAuthenticated() || $this->getUserRole() !== 'proprietaire') {
            $this->redirect('/');
            return;
        }

        $proprietaireId = $this->getUserId();
        $statut = $this->get('statut') ?? 'nouveau';

        $demandeModel = $this->model('DemandeInteret');

        // Récupérer les demandes filtrées par statut
        if ($statut === 'tous') {
            $demandes = $demandeModel->getByProprietaire($proprietaireId);
        } else {
            $demandes = $demandeModel->getByProprietaireAndStatut($proprietaireId, $statut);
        }

        // Compter les demandes par statut
        $counts = [
            'nouveau' => $demandeModel->countByStatutForProprietaire($proprietaireId, 'nouveau'),
            'vue' => $demandeModel->countByStatutForProprietaire($proprietaireId, 'vue'),
            'accepte' => $demandeModel->countByStatutForProprietaire($proprietaireId, 'accepte'),
            'refuse' => $demandeModel->countByStatutForProprietaire($proprietaireId, 'refuse')
        ];

        $this->view('proprietaire/demandes-interet', [
            'demandes' => $demandes,
            'statut' => $statut,
            'counts' => $counts,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Met à jour le statut d'une demande
     */
    public function updateStatut(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/');
            return;
        }

        if (!$this->isAuthenticated() || $this->getUserRole() !== 'proprietaire') {
            $this->redirect('/');
            return;
        }

        $demandeId = (int)$this->post('demande_id');
        $statut = $this->post('statut');

        // Validation du statut
        $statuts_valides = ['nouveau', 'vue', 'accepte', 'refuse'];
        if (!in_array($statut, $statuts_valides)) {
            $this->setFlash('Statut invalide.', 'error');
            $this->redirect('/proprietaire/demandes-interet');
            return;
        }

        // Vérifier que la demande existe et appartient au propriétaire
        $demandeModel = $this->model('DemandeInteret');
        $demande = $demandeModel->findById($demandeId);

        if (!$demande) {
            $this->setFlash('Demande introuvable.', 'error');
            $this->redirect('/proprietaire/demandes-interet');
            return;
        }

        // Vérifier que la demande appartient au propriétaire
        $annonceModel = $this->model('Annonce');
        $annonce = $annonceModel->findById($demande['annonce_id']);

        if (!$annonce || $annonce['proprietaire_id'] !== $this->getUserId()) {
            $this->setFlash('Vous n\'avez pas accès à cette demande.', 'error');
            $this->redirect('/proprietaire/demandes-interet');
            return;
        }

        // Mettre à jour le statut
        try {
            $demandeModel->updateStatut($demandeId, $statut);
            $this->setFlash('Statut mis à jour avec succès.', 'success');
        } catch (Exception $e) {
            $this->setFlash('Une erreur est survenue lors de la mise à jour du statut.', 'error');
        }

        $this->redirect('/proprietaire/demandes-interet');
    }

    /**
     * Affiche les détails d'une demande
     */
    public function show(int $id): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }

        $demandeModel = $this->model('DemandeInteret');
        $demande = $demandeModel->findById($id);

        if (!$demande) {
            $this->setFlash('Demande introuvable.', 'error');
            $this->redirect('/proprietaire/demandes-interet');
            return;
        }

        // Vérifier l'accès (propriétaire ou étudiant)
        $userId = $this->getUserId();
        $userRole = $this->getUserRole();

        if ($userRole === 'proprietaire') {
            // Vérifier que c'est le propriétaire de l'annonce
            $annonceModel = $this->model('Annonce');
            $annonce = $annonceModel->findById($demande['annonce_id']);

            if (!$annonce || $annonce['proprietaire_id'] !== $userId) {
                $this->setFlash('Vous n\'avez pas accès à cette demande.', 'error');
                $this->redirect('/proprietaire/demandes-interet');
                return;
            }

            // Marquer comme vue
            if ($demande['statut'] === 'nouveau') {
                $demandeModel->markAsViewed($id);
            }
        } elseif ($userRole === 'etudiant' && $demande['etudiant_id'] !== $userId) {
            $this->setFlash('Vous n\'avez pas accès à cette demande.', 'error');
            $this->redirect('/');
            return;
        }

        $this->view('demandes-interet/show', [
            'demande' => $demande,
            'flash' => $this->getFlash()
        ]);
    }
}
