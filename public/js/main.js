/**
 * STUD_HOME - JavaScript Principal
 * Gestion de Logements Étudiants
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-hide flash messages après 5 secondes
    const flashMessages = document.querySelectorAll('.flash, .alert');
    flashMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 300);
        }, 5000);
    });
    
    // Confirmation avant suppression avec modal personnalisée
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.getAttribute('data-confirm') || 'Êtes-vous sûr de vouloir supprimer cet élément ?';
            const url = this.href || this.getAttribute('data-url');
            
            showConfirmModal(message, function() {
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
    
    // Validation du formulaire d'inscription
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');
            
            if (password.value !== passwordConfirm.value) {
                e.preventDefault();
                showErrorNotification('Les mots de passe ne correspondent pas.');
                passwordConfirm.focus();
                return false;
            }
            
            if (password.value.length < 8) {
                e.preventDefault();
                showErrorNotification('Le mot de passe doit contenir au moins 8 caractères.');
                password.focus();
                return false;
            }
        });
    }
    
    // Preview d'image avant upload
    const photoInput = document.querySelector('input[type="file"][name="photo"]');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('photoPreview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'photoPreview';
                        preview.style.maxWidth = '300px';
                        preview.style.marginTop = '10px';
                        preview.style.borderRadius = '8px';
                        photoInput.parentElement.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Recherche dynamique dans les tableaux
    const searchInputs = document.querySelectorAll('[data-search]');
    searchInputs.forEach(function(input) {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const targetTable = document.querySelector(this.getAttribute('data-search'));
            const rows = targetTable.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
});

/**
 * Pagination AJAX pour les annonces
 */
document.addEventListener('DOMContentLoaded', function() {
    const paginationContainer = document.getElementById('pagination-container');
    
    if (paginationContainer) {
        // Intercepter les clics sur les liens de pagination
        paginationContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('page-link')) {
                e.preventDefault();
                
                const url = e.target.getAttribute('href');
                if (!url) return;
                
                // Extraire le numéro de page depuis l'URL
                const urlParams = new URLSearchParams(url.split('?')[1]);
                const page = urlParams.get('page');
                
                if (page) {
                    loadAnnonces(page);
                }
            }
        });
    }
});

/**
 * Charger les annonces via AJAX
 */
function loadAnnonces(page) {
    const container = document.getElementById('annonces-container');
    const paginationContainer = document.getElementById('pagination-container');
    
    if (!container) return;
    
    // Afficher un indicateur de chargement
    container.style.opacity = '0.5';
    container.style.pointerEvents = 'none';
    
    // Construire l'URL avec le numéro de page
    const baseUrl = window.location.origin + window.location.pathname;
    const url = `${baseUrl}?page=${page}`;
    
    // Requête AJAX
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Créer un élément temporaire pour parser le HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Extraire le contenu des annonces
        const newAnnonces = doc.getElementById('annonces-container');
        const newPagination = doc.getElementById('pagination-container');
        
        if (newAnnonces) {
            container.innerHTML = newAnnonces.innerHTML;
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
        
        if (newPagination && paginationContainer) {
            paginationContainer.innerHTML = newPagination.innerHTML;
            paginationContainer.setAttribute('data-current-page', page);
        }
        
        // Mettre à jour l'URL dans le navigateur sans recharger
        window.history.pushState({page: page}, '', url);
        
        // Scroller en haut de la liste
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    })
    .catch(error => {
        console.error('Erreur lors du chargement des annonces:', error);
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';
        showNotification('Erreur lors du chargement des annonces', 'error');
    });
}

// Gérer les boutons précédent/suivant du navigateur
window.addEventListener('popstate', function(e) {
    if (e.state && e.state.page) {
        loadAnnonces(e.state.page);
    } else {
        location.reload();
    }
});

/**
 * Fonction utilitaire pour formater les prix
 */
function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}

// Les fonctions de notification sont maintenant dans notifications.js
