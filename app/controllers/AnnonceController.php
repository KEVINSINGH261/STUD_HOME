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
        $annonces = $annonceModel->findAllWithProprietaire();
        
        $this->view('annonces/index', [
            'annonces' => $annonces,
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
        
        // Vérifier si en favori (si étudiant connecté)
        $isFavori = false;
        if ($this->isAuthenticated() && $this->getUserRole() === 'etudiant') {
            $favoriModel = $this->model('Favori');
            $isFavori = $favoriModel->exists($this->getUserId(), $id);
        }
        
        $this->view('annonces/show', [
            'annonce' => $annonce,
            'isFavori' => $isFavori,
            'flash' => $this->getFlash()
        ]);
    }
}
