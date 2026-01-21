<?php
/**
 * Contrôleur AnnonceController - Gestion des annonces (partie publique)
 */
class AnnonceController extends Controller
{
    /**
     * Liste toutes les annonces
     */
    public function index(): void
    {
        $annonceModel = $this->model('Annonce');
        
        // Pagination
        $perPage = 9;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;
        
        // Récupérer les annonces paginées
        $annonces = $annonceModel->findAllWithProprietairePaginated($perPage, $offset);
        $totalAnnonces = $annonceModel->countActive();
        $totalPages = ceil($totalAnnonces / $perPage);
        
        // Ajouter l'image principale à chaque annonce
        foreach ($annonces as &$annonce) {
            $annonce['image_principale'] = $annonceModel->getImagePrincipale($annonce['id']);
        }
        
        $this->view('annonces/index', [
            'annonces' => $annonces,
            'filters' => ['ville' => '', 'prix_min' => '', 'prix_max' => '', 'type' => []],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Recherche d'annonces avec filtres
     */
    public function search(): void
    {
        $filters = [
            'ville' => $this->get('ville'),
            'prix_min' => $this->get('prix_min'),
            'prix_max' => $this->get('prix_max'),
            'type' => $this->get('type'), // This will be an array from type[]
            'sort' => $this->get('sort') // Add sort parameter
        ];
        
        $annonceModel = $this->model('Annonce');
        $annonces = $annonceModel->search($filters);
        
        // Ajouter l'image principale à chaque annonce
        foreach ($annonces as &$annonce) {
            $annonce['image_principale'] = $annonceModel->getImagePrincipale($annonce['id']);
        }
        
        $this->view('annonces/index', [
            'annonces' => $annonces,
            'filters' => $filters,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Affiche le détail d'une annonce
     */
    public function show(int $id): void
    {
        $annonceModel = $this->model('Annonce');
        $annonce = $annonceModel->findByIdWithProprietaire($id);
        
        if (!$annonce) {
            $this->setFlash('error', 'Annonce introuvable.');
            $this->redirect('annonces');
            return;
        }
        
        // Récupérer les images de l'annonce
        $images = $annonceModel->getImages($id);
        
        // Ajouter l'image principale à l'annonce
        $annonce['image_principale'] = $annonceModel->getImagePrincipale($id);
        
        // Vérifier si en favori (si étudiant connecté)
        $isFavori = false;
        if ($this->isAuthenticated() && $this->getUserRole() === 'etudiant') {
            $favoriModel = $this->model('Favori');
            $isFavori = $favoriModel->exists($this->getUserId(), $id);
        }
        
        $this->view('annonces/show', [
            'annonce' => $annonce,
            'images' => $images,
            'isFavori' => $isFavori,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Traite le signalement d'une annonce
     */
    public function report(int $id): void
    {
        // Vérifier que l'utilisateur est connecté
        if (!$this->isAuthenticated()) {
            $this->setFlash('error', 'Vous devez être connecté pour signaler une annonce.');
            $this->redirect('annonces/details/' . $id);
            return;
        }
        
        // Traiter le formulaire POST
        if ($this->isPost()) {
            $motif = $this->post('motif');
            $description = $this->post('description');
            
            // Validation
            if (!$motif || !in_array($motif, ['spam', 'arnaque', 'contenu_inapproprie', 'annonce_disparue', 'autre'])) {
                $this->setFlash('error', 'Motif de signalement invalide.');
                $this->redirect('annonces/details/' . $id);
                return;
            }
            
            $signalementModel = $this->model('Signalement');
            $userId = $this->getUserId();
            
            // Vérifier si l'utilisateur a déjà signalé cette annonce
            if ($signalementModel->exists($id, $userId)) {
                $this->setFlash('error', 'Vous avez déjà signalé cette annonce.');
                $this->redirect('annonces/details/' . $id);
                return;
            }
            
            // Créer le signalement
            if ($signalementModel->create($id, $userId, $motif, $description)) {
                $this->setFlash('success', 'Merci ! Votre signalement a été enregistré et sera examiné par notre équipe.');
            } else {
                $this->setFlash('error', 'Une erreur est survenue lors du signalement.');
            }
            
            $this->redirect('annonces/details/' . $id);
            return;
        }
        
        // Afficher le formulaire de signalement
        $annonceModel = $this->model('Annonce');
        $annonce = $annonceModel->findByIdWithProprietaire($id);
        
        if (!$annonce) {
            $this->setFlash('error', 'Annonce introuvable.');
            $this->redirect('annonces');
            return;
        }
        
        $this->view('annonces/report', [
            'annonce' => $annonce,
            'flash' => $this->getFlash()
        ]);
    }
}
