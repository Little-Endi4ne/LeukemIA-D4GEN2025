// Fonction pour charger les composants (header, footer)
document.addEventListener('DOMContentLoaded', function() {
    // Charger le header
    fetch('header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
            updateLoginStatus(); // Mettre à jour l'état de connexion après le chargement du header
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
    if (username) {
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

// Fonction de connexion (à appeler depuis login.html)
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
// Fonction pour charger les composants (header, footer)
document.addEventListener('DOMContentLoaded', function() {
    // Charger le header
    fetch('header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
            updateLoginStatus(); // Mettre à jour l'état de connexion après le chargement du header
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
    // Pas de redirection vers login.html, même si l'utilisateur n'est pas connecté
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



// Gestion du menu mobile
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments du DOM pour le menu mobile
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuOverlay = document.getElementById('menuOverlay');
    const closeMenu = document.getElementById('closeMenu');
    
    // Fonction pour ouvrir le menu mobile
    function openMobileMenu() {
        if (mobileMenu && menuOverlay) {
            mobileMenu.classList.add('active');
            menuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
        }
    }
    
    // Fonction pour fermer le menu mobile
    function closeMobileMenu() {
        if (mobileMenu && menuOverlay) {
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Réactive le défilement de la page
        }
    }
    
    // Ajouter les écouteurs d'événements
    if (menuToggle) {
        menuToggle.addEventListener('click', openMobileMenu);
    }
    
    if (closeMenu) {
        closeMenu.addEventListener('click', closeMobileMenu);
    }
    
    if (menuOverlay) {
        menuOverlay.addEventListener('click', closeMobileMenu);
    }
    
    // Gérer le lien de déconnexion
    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
});