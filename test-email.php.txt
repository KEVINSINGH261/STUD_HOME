<?php
// Charger l'autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Charger la config
require_once __DIR__ . '/config/config.php';

// Charger le service
require_once __DIR__ . '/app/services/EmailService.php';

echo "<h1>Test d'envoi d'email - Stud'Home</h1>";

try {
    $emailService = new EmailService();
    
    // REMPLACE PAR TON EMAIL POUR TESTER
    $testEmail = 'alkaly156@gmail.com';
    $testToken = 'test123456789abcdef';
    
    echo "<p>📧 Envoi en cours vers : <strong>$testEmail</strong>...</p>";
    
    if ($emailService->sendPasswordResetEmail($testEmail, $testToken)) {
        echo "<p style='color: green; font-size: 18px;'>✅ <strong>Email envoyé avec succès !</strong></p>";
        echo "<p>🔍 Vérifie ta boîte mail (et les spams si besoin)</p>";
        echo "<p>Le lien de test : <code>" . APP_URL . "/reset-password?token=$testToken</code></p>";
    } else {
        echo "<p style='color: red; font-size: 18px;'>❌ <strong>Erreur lors de l'envoi</strong></p>";
        echo "<p>Vérifie tes identifiants Gmail dans config/mail.php</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Stack trace :</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>
```

6. **Sauvegarde** le fichier

Ensuite, ouvre ton navigateur et va sur :
```
http://localhost/STUD_HOME/test-email.php