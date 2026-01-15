<?php
/**
 * FooterController
 * Gestion des pages du footer (liens légaux)
 */

class FooterController {
    
    /**
     * Afficher la page des paramètres cookies
     */
    public function parametresCookies() {
        require_once VIEWS_PATH . '/lien-footer/parametres-cookies.php';
    }
    
    /**
     * Sauvegarder les préférences de cookies
     */
    public function saveCookiePreferences() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les préférences depuis le formulaire
            $preferences = [
                'essential' => true, // Toujours activé
                'functional' => isset($_POST['functional']),
                'analytics' => isset($_POST['analytics']),
                'marketing' => isset($_POST['marketing'])
            ];
            
            // Sauvegarder dans un cookie (durée: 1 an)
            setcookie(
                'cookie_preferences', 
                json_encode($preferences), 
                time() + (365 * 24 * 60 * 60), 
                '/'
            );
            
            // Message flash de succès
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Vos préférences ont été enregistrées avec succès !'
            ];
            
            // Rediriger vers la page des paramètres
            header('Location: ' . APP_URL . '/parametres-cookies');
            exit;
        }
        
        // Si ce n'est pas une requête POST, rediriger
        header('Location: ' . APP_URL . '/parametres-cookies');
        exit;
    }
}