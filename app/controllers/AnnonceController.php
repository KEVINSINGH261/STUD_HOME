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
            'type' => $this->get('type')
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
}
