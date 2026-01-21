<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-links">
                <a href="<?= APP_URL ?>/parametres-cookies">Paramètres cookies</a>
                <a href="<?= APP_URL ?>/equipe">À propos</a>
                <a href="<?= APP_URL ?>/protection-donnees">Protections des Données</a>
                <a href="#">Nous Contacter</a>
                <a href="#">Recrutement</a>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© 1Dev. STUD'HOME</p>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer {
    background-color: #ffffff;
    padding: 40px 0 30px;
    border-top: 1px solid #e0e0e0;
    margin-top: 60px;
}

.footer .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-content {
    margin-bottom: 25px;
}

.footer-links {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 0;
}

.footer-links a {
    color: #666666;
    text-decoration: none;
    font-size: 14px;
    padding: 8px 20px;
    border-right: 1px solid #e0e0e0;
    transition: color 0.3s ease;
    white-space: nowrap;
}

.footer-links a:last-child {
    border-right: none;
}

.footer-links a:hover {
    color: #FF6B6B;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}

.footer-bottom p {
    color: #999999;
    font-size: 13px;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .footer {
        padding: 30px 0 20px;
    }
    
    .footer-links {
        flex-direction: column;
        gap: 10px;
    }
    
    .footer-links a {
        border-right: none;
        padding: 8px 0;
    }
}
</style>