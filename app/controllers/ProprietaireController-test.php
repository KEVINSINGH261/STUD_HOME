<?php
/**
 * ProprietaireController
 * Gestion des annonces pour les propriétaires
 */

class ProprietaireController {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Afficher le tableau de bord du propriétaire
     */
    public function dashboard() {
        // Vérifier que l'utilisateur est connecté et est propriétaire
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'proprietaire') {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        // Récupérer les informations du propriétaire
        try {
            $sql = "SELECT * FROM proprietaires WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $proprietaire = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$proprietaire) {
                // Si le propriétaire n'existe pas, déconnecter
                session_destroy();
                header('Location: ' . APP_URL . '/login');
                exit;
            }
            
            // Charger la vue du dashboard
            require_once VIEWS_PATH . '/proprietaire/dashboard.php';
            
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
    
    /**
     * Afficher le formulaire de création d'annonce
     */
    public function createAnnonce() {
        // Vérifier que l'utilisateur est connecté et est propriétaire
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'proprietaire') {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        require_once VIEWS_PATH . '/proprietaire/create-annonce.php';
    }
    
    /**
     * Enregistrer une nouvelle annonce
     */
    public function storeAnnonce() {
        // Vérifier que l'utilisateur est connecté et est propriétaire
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'proprietaire') {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/proprietaire/annonces/create');
            exit;
        }
        
        // Récupérer les données du formulaire
        $titre = trim($_POST['titre'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $code_postal = trim($_POST['code_postal'] ?? '');
        $surface = intval($_POST['surface'] ?? 0);
        $nombre_chambres = intval($_POST['nombre_chambres'] ?? 0);
        $prix = floatval($_POST['prix'] ?? 0);
        $statut = trim($_POST['statut'] ?? 'inactive');
        $proprietaire_id = $_SESSION['user_id'];
        
        // Validation
        $errors = [];
        
        if (empty($titre) || strlen($titre) < 5) {
            $errors[] = "Le titre doit contenir au moins 5 caractères.";
        }
        
        if (empty($type) || !in_array($type, ['studio', 'appartement', 'maison', 'chambre'])) {
            $errors[] = "Type de logement invalide.";
        }
        
        if (empty($description) || strlen($description) < 50) {
            $errors[] = "La description doit contenir au moins 50 caractères.";
        }
        
        if (empty($adresse) || empty($ville) || empty($code_postal)) {
            $errors[] = "L'adresse complète est requise.";
        }
        
        if (!preg_match('/^[0-9]{5}$/', $code_postal)) {
            $errors[] = "Le code postal doit contenir 5 chiffres.";
        }
        
        if ($surface <= 0) {
            $errors[] = "La surface doit être supérieure à 0.";
        }
        
        if ($nombre_chambres < 0) {
            $errors[] = "Le nombre de chambres ne peut pas être négatif.";
        }
        
        if ($prix <= 0) {
            $errors[] = "Le prix doit être supérieur à 0.";
        }
        
        // Si erreurs, rediriger
        if (!empty($errors)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => implode('<br>', $errors)
            ];
            header('Location: ' . APP_URL . '/proprietaire/annonces/create');
            exit;
        }
        
        // Gestion de l'upload de la photo
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5Mo
            
            if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Format de fichier non accepté. Utilisez JPG, PNG, GIF ou WebP.'
                ];
                header('Location: ' . APP_URL . '/proprietaire/annonces/create');
                exit;
            }
            
            if ($_FILES['photo']['size'] > $maxSize) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'La photo ne doit pas dépasser 5Mo.'
                ];
                header('Location: ' . APP_URL . '/proprietaire/annonces/create');
                exit;
            }
            
            // Créer le dossier uploads s'il n'existe pas
            $uploadDir = 'uploads/annonces/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Générer un nom de fichier unique
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('annonce_') . '.' . $extension;
            $photoPath = $uploadDir . $filename;
            
            // Déplacer le fichier
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Erreur lors de l\'upload de la photo.'
                ];
                header('Location: ' . APP_URL . '/proprietaire/annonces/create');
                exit;
            }
        }
        
        // Insérer en base de données
        try {
            $sql = "INSERT INTO annonces (proprietaire_id, titre, type, description, adresse, ville, 
                    code_postal, surface, nombre_chambres, prix, photo, statut, date_creation) 
                    VALUES (:proprietaire_id, :titre, :type, :description, :adresse, :ville, 
                    :code_postal, :surface, :nombre_chambres, :prix, :photo, :statut, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':proprietaire_id' => $proprietaire_id,
                ':titre' => $titre,
                ':type' => $type,
                ':description' => $description,
                ':adresse' => $adresse,
                ':ville' => $ville,
                ':code_postal' => $code_postal,
                ':surface' => $surface,
                ':nombre_chambres' => $nombre_chambres,
                ':prix' => $prix,
                ':photo' => $photoPath,
                ':statut' => $statut
            ]);
            
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Votre annonce a été créée avec succès !'
            ];
            
            header('Location: ' . APP_URL . '/proprietaire/dashboard');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Erreur lors de la création de l\'annonce : ' . $e->getMessage()
            ];
            header('Location: ' . APP_URL . '/proprietaire/annonces/create');
            exit;
        }
    }
    
    /**
     * Afficher la liste des annonces du propriétaire
     */
    public function annonces() {
        // Vérifier que l'utilisateur est connecté et est propriétaire
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'proprietaire') {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        try {
            // Récupérer toutes les annonces du propriétaire
            $sql = "SELECT * FROM annonces WHERE proprietaire_id = :proprietaire_id ORDER BY date_creation DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':proprietaire_id' => $_SESSION['user_id']]);
            $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $flash = $_SESSION['flash'] ?? null;
            unset($_SESSION['flash']);
            
            require_once VIEWS_PATH . '/proprietaire/annonces.php';
            
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}