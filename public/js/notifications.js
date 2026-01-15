/**
 * notifications.js
 * Système de notifications et modales user-friendly
 * Remplace les alert() et confirm() natifs du navigateur
 */

/**
 * Fonction pour afficher une notification
 * @param {string} message - Le message à afficher
 * @param {string} type - Le type de notification (info, success, error)
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `flash flash-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.style.animation = 'slideIn 0.3s ease';
    
    document.body.appendChild(notification);
    
    setTimeout(function() {
        notification.style.opacity = '0';
        setTimeout(function() {
            notification.remove();
        }, 300);
    }, 5000);
}

/**
 * Fonction pour afficher une notification d'erreur
 * @param {string} message - Le message d'erreur à afficher
 */
function showErrorNotification(message) {
    showNotification(message, 'error');
}

/**
 * Fonction pour afficher une notification de succès
 * @param {string} message - Le message de succès à afficher
 */
function showSuccessNotification(message) {
    showNotification(message, 'success');
}

/**
 * Fonction pour afficher une notification d'information
 * @param {string} message - Le message d'information à afficher
 */
function showInfoNotification(message) {
    showNotification(message, 'info');
}

/**
 * Fonction pour afficher une modale de confirmation
 * @param {string} message - Le message de confirmation
 * @param {function} onConfirm - Callback à exécuter si l'utilisateur confirme
 * @param {function} onCancel - Callback optionnel à exécuter si l'utilisateur annule
 */
function showConfirmModal(message, onConfirm, onCancel) {
    // Créer l'overlay
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        animation: fadeIn 0.2s ease;
    `;
    
    // Créer la modale
    const modal = document.createElement('div');
    modal.className = 'confirm-modal';
    modal.style.cssText = `
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 400px;
        width: 90%;
        animation: slideUp 0.3s ease;
    `;
    
    modal.innerHTML = `
        <h3 style="margin: 0 0 1rem 0; color: #333; font-size: 1.25rem;">Confirmation</h3>
        <p style="margin: 0 0 1.5rem 0; color: #666; line-height: 1.6;">${message}</p>
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <button class="btn-cancel" style="
                padding: 0.75rem 1.5rem;
                border: 1px solid #e0e0e0;
                background: white;
                color: #666;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.95rem;
                transition: all 0.3s;
            ">Annuler</button>
            <button class="btn-confirm" style="
                padding: 0.75rem 1.5rem;
                border: none;
                background: #FF6B6B;
                color: white;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.95rem;
                font-weight: 500;
                transition: all 0.3s;
            ">Confirmer</button>
        </div>
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Gestion des boutons
    const btnCancel = modal.querySelector('.btn-cancel');
    const btnConfirm = modal.querySelector('.btn-confirm');
    
    btnCancel.addEventListener('mouseover', function() {
        this.style.background = '#f5f5f5';
    });
    
    btnCancel.addEventListener('mouseout', function() {
        this.style.background = 'white';
    });
    
    btnConfirm.addEventListener('mouseover', function() {
        this.style.background = '#ff5252';
    });
    
    btnConfirm.addEventListener('mouseout', function() {
        this.style.background = '#FF6B6B';
    });
    
    function closeModal() {
        overlay.style.opacity = '0';
        setTimeout(function() {
            overlay.remove();
        }, 200);
    }
    
    btnCancel.addEventListener('click', function() {
        closeModal();
        if (onCancel) onCancel();
    });
    
    btnConfirm.addEventListener('click', function() {
        closeModal();
        if (onConfirm) onConfirm();
    });
    
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeModal();
            if (onCancel) onCancel();
        }
    });
    
    // Gestion de la touche ESC
    const handleEscape = function(e) {
        if (e.key === 'Escape') {
            closeModal();
            if (onCancel) onCancel();
            document.removeEventListener('keydown', handleEscape);
        }
    };
    
    document.addEventListener('keydown', handleEscape);
    
    // Focus sur le bouton confirmer
    btnConfirm.focus();
}
