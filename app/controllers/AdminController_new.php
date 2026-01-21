    /**
     * Liste des signalements
     */
    public function signalements(): void
    {
        $signalementModel = $this->model('Signalement');
        $statut = $this->get('statut') ?? 'nouveau';
        
        // Récupérer les signalements par statut
        if ($statut === 'tous') {
            $signalements = $signalementModel->getAll();
        } else {
            $signalements = $signalementModel->getByStatut($statut);
        }
        
        // Compter les signalements par statut
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
