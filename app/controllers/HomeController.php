<?php
/**
 * Contrôleur Home - Page d'accueil
 */
class HomeController extends Controller
{
    /**
     * Page d'accueil
     */
    public function index(): void
    {
        $annonceModel = $this->model('Annonce');
        
        // Récupérer les dernières annonces
        $annonces = $annonceModel->findRecent(6);
        
        // Compter le nombre total d'annonces
        $totalAnnonces = $annonceModel->countByStatut('active');
        
        $this->view('home/index', [
            'annonces' => $annonces,
            'totalAnnonces' => $totalAnnonces,
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Page équipe
     */
    public function equipe(): void
    {
        $this->view('pages/equipe');
    }
    
    /**
     * Page FAQ
     */
    public function faq(): void
    {
        $this->view('pages/faq');
    }
}
