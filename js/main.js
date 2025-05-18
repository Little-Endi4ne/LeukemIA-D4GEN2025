// Fonction pour charger les composants (header, footer)
document.addEventListener('DOMContentLoaded', function() {
    // Charger le header
    fetch('header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
            updateLoginStatus(); // Mettre à jour l'état de connexion après le chargement du header
            
            // Configuration du menu mobile après que le header soit chargé
            setupMobileMenu();
        })
        .catch(error => console.error('Erreur lors du chargement du header:', error));

    // Charger le footer
    fetch('footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer').innerHTML = data;
        })
        .catch(error => console.error('Erreur lors du chargement du footer:', error));
    
    // Charger le nom d'utilisateur s'il est connecté
    const username = getUsernameFromStorage();
    if (username && document.getElementById('username')) {
        document.getElementById('username').textContent = username;
    }
});

// Fonction pour mettre à jour l'affichage selon l'état de connexion
function updateLoginStatus() {
    const loginLink = document.getElementById('login-link');
    const logoutLink = document.getElementById('logout-link');
    const username = getUsernameFromStorage();
    
    if (username) {
        // Utilisateur connecté
        if (loginLink) loginLink.style.display = 'none';
        if (logoutLink) logoutLink.style.display = 'inline-block';
    } else {
        // Utilisateur non connecté
        if (loginLink) loginLink.style.display = 'inline-block';
        if (logoutLink) logoutLink.style.display = 'none';
    }
}

// Fonction pour récupérer le nom d'utilisateur du stockage local
function getUsernameFromStorage() {
    return localStorage.getItem('username');
}

// Fonction de déconnexion
function logout() {
    localStorage.removeItem('username');
    localStorage.removeItem('isLoggedIn');
    window.location.href = 'login.html';
}

// Configuration du menu mobile
function setupMobileMenu() {
    console.log('Configuration du menu mobile');
    
    // Récupérer les éléments du DOM pour le menu mobile
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuOverlay = document.getElementById('menuOverlay');
    const closeMenu = document.getElementById('closeMenu');
    
    // Vérifier que les éléments existent
    if (!menuToggle || !mobileMenu || !menuOverlay || !closeMenu) {
        console.error('Un ou plusieurs éléments du menu mobile sont manquants');
        return;
    }
    
    // Fonction pour ouvrir le menu mobile
    function openMobileMenu() {
        console.log('Ouverture du menu mobile');
        mobileMenu.classList.add('active');
        menuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
    }
    
    // Fonction pour fermer le menu mobile
    function closeMobileMenu() {
        console.log('Fermeture du menu mobile');
        mobileMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Réactive le défilement de la page
    }
    
    // Ajouter les écouteurs d'événements
    menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        openMobileMenu();
    });
    
    closeMenu.addEventListener('click', function(e) {
        e.preventDefault();
        closeMobileMenu();
    });
    
    menuOverlay.addEventListener('click', function(e) {
        e.preventDefault();
        closeMobileMenu();
    });
    
    // Gérer le lien de déconnexion
    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}

// Connexion (à appeler depuis login.html)
function login(username, password) {
    // Dans une version réelle, vous utiliseriez une API pour vérifier les identifiants
    // Pour cette démo, on accepte n'importe quel nom d'utilisateur avec le mot de passe "demo"
    if (password === 'demo') {
        localStorage.setItem('username', username);
        localStorage.setItem('isLoggedIn', 'true');
        window.location.href = 'index.html';
        return true;
    } else {
        alert('Identifiants incorrects. Utilisez n\'importe quel nom avec le mot de passe "demo"');
        return false;
    }
}