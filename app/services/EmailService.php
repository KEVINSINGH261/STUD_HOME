<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;
    
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        
        // Configuration SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = MAIL_HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = MAIL_USERNAME;
        $this->mailer->Password = MAIL_PASSWORD;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = MAIL_PORT;
        $this->mailer->CharSet = 'UTF-8';
        
        // Expéditeur par défaut
        $this->mailer->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
    }
    
    /**
     * Envoyer un email de réinitialisation de mot de passe
     */
    public function sendPasswordResetEmail($email, $token)
    {
        try {
            // Destinataire
            $this->mailer->addAddress($email);
            
            // Lien de réinitialisation
            $resetLink = APP_URL . '/reset-password?token=' . $token;
            
            // Sujet
            $this->mailer->Subject = 'Réinitialisation de votre mot de passe - Stud\'Home';
            
            // Corps de l'email en HTML
            $this->mailer->isHTML(true);
            $this->mailer->Body = $this->getPasswordResetTemplate($resetLink);
            
            // Texte alternatif
            $this->mailer->AltBody = "Bonjour,\n\nVous avez demandé la réinitialisation de votre mot de passe.\n\nCliquez sur ce lien :\n$resetLink\n\nCe lien est valable 1 heure.\n\nCordialement,\nL'équipe Stud'Home";
            
            // Envoi
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email : " . $this->mailer->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Envoyer un email de suppression de compte
     */
    public function sendAccountDeletionEmail($email, $userName = null)
    {
        try {
            // Réinitialiser le mailer pour éviter les doublons d'adresses
            $this->mailer->clearAddresses();
            
            // Destinataire
            $this->mailer->addAddress($email);
            
            // Sujet
            $this->mailer->Subject = 'Suppression de votre compte - Stud\'Home';
            
            // Corps de l'email en HTML
            $this->mailer->isHTML(true);
            $this->mailer->Body = $this->getAccountDeletionTemplate($userName);
            
            // Texte alternatif
            $this->mailer->AltBody = "Bonjour,\n\nVotre compte Stud'Home a été supprimé par un administrateur.\n\nSi vous pensez qu'il s'agit d'une erreur, veuillez contacter le support.\n\nCordialement,\nL'équipe Stud'Home";
            
            // Envoi
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email de suppression : " . $this->mailer->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Template HTML pour l'email
     */
    private function getPasswordResetTemplate($resetLink)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Stud'Home</h1>
                    <p>Réinitialisation de mot de passe</p>
                </div>
                <div class='content'>
                    <h2>Bonjour,</h2>
                    <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                    <p>Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
                    <div style='text-align: center;'>
                        <a href='$resetLink' class='button'>Réinitialiser mon mot de passe</a>
                    </div>
                    <p><strong>Ce lien est valable pendant 1 heure.</strong></p>
                    <p style='color: #666; font-size: 14px;'>Si le bouton ne fonctionne pas, copiez ce lien :<br>
                    <a href='$resetLink'>$resetLink</a></p>
                    <hr style='margin: 30px 0; border: none; border-top: 1px solid #ddd;'>
                    <p style='color: #999; font-size: 13px;'>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
                </div>
                <div class='footer'>
                    <p> 2025 Stud'Home - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Template HTML pour l'email de suppression de compte
     */
    private function getAccountDeletionTemplate($userName = null)
    {
        $greeting = $userName ? "Bonjour $userName," : "Bonjour,";
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .warning { background: #fff3cd; border-left: 4px solid #ff6b6b; padding: 15px; margin: 20px 0; border-radius: 5px; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Stud'Home</h1>
                    <p>Suppression de compte</p>
                </div>
                <div class='content'>
                    <h2>$greeting</h2>
                    <p>Nous vous informons que votre compte Stud'Home a été supprimé par un administrateur.</p>
                    <div class='warning'>
                        <p><strong>Important :</strong> Toutes les données associées à votre compte ont été supprimées de manière permanente.</p>
                    </div>
                    <p><strong>Ce que cela signifie :</strong></p>
                    <ul>
                        <li>Vous ne pouvez plus accéder à votre compte</li>
                        <li>Vos annonces (si propriétaire) ont été supprimées</li>
                        <li>Vos favoris ont été supprimés</li>
                        <li>Toutes vos données personnelles ont été effacées</li>
                    </ul>
                    <hr style='margin: 30px 0; border: none; border-top: 1px solid #ddd;'>
                    <p style='color: #666; font-size: 14px;'><strong>Support :</strong> Si vous pensez qu'il s'agit d'une erreur ou si vous avez des questions, veuillez contacter notre équipe support.</p>
                </div>
                <div class='footer'>
                    <p> 2025 Stud'Home - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
