<?php
/**
 * Définition des routes de l'application
 * STUD_HOME - Gestion de Logements Étudiants
 * 
 * Format: 'URL' => 'Controller@method'
 */

return [
    // Routes publiques
    '' => 'HomeController@index',
    '/' => 'HomeController@index',
    'home' => 'HomeController@index',
    
    // Authentification
    'login' => 'AuthController@showLogin',
    'login/submit' => 'AuthController@login',
    'register' => 'AuthController@showRegister',
    'register/submit' => 'AuthController@register',
    'register/check-email' => 'AuthController@checkEmail',
    'logout' => 'AuthController@logout',
    
    // Récupération de mot de passe avec question de sécurité
    'forgot-password' => 'AuthController@showForgotPassword',
    'forgot-password/submit' => 'AuthController@forgotPassword',
    'forgot-password-security' => 'AuthController@showSecurityQuestion',
    'verify-security-answer' => 'AuthController@verifySecurityAnswer',
    'reset-password' => 'AuthController@showResetPassword',
    'reset-password/submit' => 'AuthController@resetPassword',
    
    // Annonces (public)
    'annonces' => 'AnnonceController@index',
    'annonces/search' => 'AnnonceController@search',
    'annonces/details/{id}' => 'AnnonceController@show',
    'annonces/report/{id}' => 'AnnonceController@report',
    
    // Demandes d'intérêt
    'demande-interet/create' => 'DemandeInteretController@create',
    'demande-interet/{id}' => 'DemandeInteretController@show',
    'demande-interet/update-statut' => 'DemandeInteretController@updateStatut',
    
    // Espace Étudiant (authentifié)
    'etudiant/dashboard' => 'EtudiantController@dashboard',
    'etudiant/favoris' => 'EtudiantController@favoris',
    'etudiant/favoris/add/{id}' => 'EtudiantController@addFavori',
    'etudiant/favoris/remove/{id}' => 'EtudiantController@removeFavori',
    'etudiant/profile' => 'EtudiantController@profile',
    'etudiant/profile/update' => 'EtudiantController@updateProfile',
    
    // Espace Propriétaire (authentifié)
    'proprietaire/dashboard' => 'ProprietaireController@dashboard',
    'proprietaire/annonces' => 'ProprietaireController@mesAnnonces',
    'proprietaire/annonces/create' => 'ProprietaireController@createAnnonce',
    'proprietaire/annonces/store' => 'ProprietaireController@storeAnnonce',
    'proprietaire/annonces/edit/{id}' => 'ProprietaireController@editAnnonce',
    'proprietaire/annonces/update/{id}' => 'ProprietaireController@updateAnnonce',
    'proprietaire/annonces/delete/{id}' => 'ProprietaireController@deleteAnnonce',
    'proprietaire/annonces/image/delete/{id}' => 'ProprietaireController@deleteImage',
    'proprietaire/annonces/image/set-main/{id}' => 'ProprietaireController@setMainImage',
    'proprietaire/profile' => 'ProprietaireController@profile',
    'proprietaire/profile/update' => 'ProprietaireController@updateProfile',
    'proprietaire/demandes-interet' => 'DemandeInteretController@list',
    
    // Espace Admin (authentifié + role admin)
    'admin/dashboard' => 'AdminController@dashboard',
    'admin/utilisateurs' => 'AdminController@utilisateurs',
    'admin/utilisateurs/delete/{id}' => 'AdminController@deleteUtilisateur',
    'admin/annonces' => 'AdminController@annonces',
    'admin/annonces/validate/{id}' => 'AdminController@validateAnnonce',
    'admin/annonces/delete/{id}' => 'AdminController@deleteAnnonce',
    'admin/signalements' => 'AdminController@signalements',
    'admin/signalements/update-statut/{id}' => 'AdminController@updateSignalementStatut',
    'admin/stats' => 'AdminController@statistics',
    
    // Pages statiques
    'equipe' => 'HomeController@equipe',
    
    // Pages footer - Liens légaux
    'parametres-cookies' => 'FooterController@parametresCookies',
    'parametres-cookies/save' => 'FooterController@saveCookiePreferences',
    'protection-donnees' => 'FooterController@protectionDonnees',
];