<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/mail.php';

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
        $logoUrl = APP_URL . '/images/logo.png';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                }
                .container { 
                    max-width: 600px; 
                    margin: 40px auto; 
                    background: #ffffff;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                }
                .header { 
                    background: linear-gradient(135deg, #FF6B6B 0%, #ff4757 100%); 
                    color: white; 
                    padding: 40px 30px; 
                    text-align: center;
                }
                .logo {
                    max-width: 150px;
                    height: auto;
                    margin-bottom: 20px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: 600;
                }
                .header p {
                    margin: 10px 0 0 0;
                    font-size: 16px;
                    opacity: 0.95;
                }
                .content { 
                    background: #ffffff; 
                    padding: 40px 30px;
                }
                .content h2 {
                    color: #333;
                    font-size: 22px;
                    margin-top: 0;
                    margin-bottom: 20px;
                }
                .content p {
                    color: #666;
                    font-size: 15px;
                    margin: 15px 0;
                }
                .button-container {
                    text-align: center;
                    margin: 30px 0;
                }
                .button { 
                    display: inline-block; 
                    padding: 15px 40px; 
                    background: #FF6B6B; 
                    color: white !important; 
                    text-decoration: none; 
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 16px;
                    transition: background 0.3s ease;
                    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
                }
                .button:hover {
                    background: #ff5252;
                }
                .info-box {
                    background: #fff5f5;
                    border-left: 4px solid #FF6B6B;
                    padding: 15px 20px;
                    margin: 25px 0;
                    border-radius: 4px;
                }
                .info-box strong {
                    color: #FF6B6B;
                }
                .link-box {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 6px;
                    margin: 20px 0;
                    word-break: break-all;
                }
                .link-box a {
                    color: #FF6B6B;
                    font-size: 13px;
                }
                .divider {
                    margin: 30px 0;
                    border: none;
                    border-top: 1px solid #e0e0e0;
                }
                .note {
                    color: #999;
                    font-size: 13px;
                    font-style: italic;
                }
                .footer { 
                    text-align: center; 
                    padding: 20px 30px;
                    background: #f8f9fa;
                    color: #666; 
                    font-size: 13px;
                }
                .footer a {
                    color: #FF6B6B;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='$logoUrl' alt='Stud\\'Home Logo' class='logo'>
                    <h1>Stud'Home</h1>
                    <p>Réinitialisation de mot de passe</p>
                </div>
                <div class='content'>
                    <h2>Bonjour,</h2>
                    <p>Vous avez demandé la réinitialisation de votre mot de passe sur <strong>Stud'Home</strong>.</p>
                    <p>Pour créer un nouveau mot de passe sécurisé, cliquez sur le bouton ci-dessous :</p>
                    <div class='button-container'>
                        <a href='$resetLink' class='button'>Réinitialiser mon mot de passe</a>
                    </div>
                    <div class='info-box'>
                        <strong>⏱ Important :</strong> Ce lien est valable pendant <strong>1 heure</strong> pour des raisons de sécurité.
                    </div>
                    <p style='color: #999; font-size: 14px;'>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
                    <div class='link-box'>
                        <a href='$resetLink'>$resetLink</a>
                    </div>
                    <hr class='divider'>
                    <p class='note'>⚠️ Si vous n'avez pas demandé cette réinitialisation, ignorez cet email. Votre mot de passe restera inchangé.</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2026 <a href='" . APP_URL . "'>Stud'Home</a> - Plateforme de logements étudiants</p>
                    <p>Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Envoyer un email personnalisé avec message texte
     */
    public function sendCustomEmail($recipientEmail, $subject, $messageText)
    {
        try {
            // Réinitialiser le mailer pour éviter les doublons d'adresses
            $this->mailer->clearAddresses();
            
            // Destinataire
            $this->mailer->addAddress($recipientEmail);
            
            // Sujet
            $this->mailer->Subject = $subject;
            
            // Corps de l'email en texte simple
            $this->mailer->isHTML(false);
            $this->mailer->Body = $messageText;
            
            // Envoi
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email personnalisé : " . $this->mailer->ErrorInfo);
            return false;
        }
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
