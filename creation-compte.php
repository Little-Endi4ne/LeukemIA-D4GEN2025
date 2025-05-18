<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté et est administrateur
// Dans un système réel, vous auriez une vérification de rôle d'administrateur
$is_admin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && ($_SESSION['username'] === 'toubib');

if (!$is_admin) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas admin
    header("Location: login.php");
    exit;
}

// Variables pour les messages
$success_message = "";
$error_message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? '';
    $specialite = $_POST['specialite'] ?? '';
    
    // Validation simple
    if (empty($nom) || empty($prenom) || empty($username) || empty($email) || empty($password)) {
        $error_message = "Tous les champs obligatoires doivent être remplis.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 8) {
        $error_message = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'adresse email n'est pas valide.";
    } else {
        // Dans une application réelle, vous enregistreriez l'utilisateur dans une base de données
        // et vous hacheriez le mot de passe avec password_hash()
        
        // Simulation d'enregistrement réussi
        $success_message = "Le compte a été créé avec succès.";
        
        // Réinitialiser les champs après succès
        $nom = $prenom = $username = $email = $password = $confirm_password = $role = $specialite = "";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeukemIA - Création de compte</title>
    <style>
        :root {
            --primary-color: #5A7D9A;
            --primary-light: #B0C4D8;
            --primary-lighter: #DDE6ED;
            --positive-color: #A8CBB7;
            --positive-light: #CFE6D3;
            --alert-color: #E79C8B;
            --alert-light: #F5C6A5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--primary-lighter);
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info span {
            font-size: 0.9rem;
        }
        
        .logout-btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        main {
            padding: 2rem 0;
        }
        
        .page-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 0.5rem;
        }
        
        .breadcrumb {
            display: flex;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .breadcrumb span {
            margin: 0 10px;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .card-title {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--primary-light);
            padding-bottom: 10px;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
            margin-bottom: 15px;
        }
        
        .form-group {
            flex: 1 0 200px;
            padding: 0 10px;
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--primary-light);
            border-radius: 4px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 125, 154, 0.2);
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: var(--positive-light);
            color: #2a5042;
            border-left: 4px solid var(--positive-color);
        }
        
        .alert-danger {
            background-color: var(--alert-light);
            color: #9a4836;
            border-left: 4px solid var(--alert-color);
        }
        
        .form-info {
            background-color: var(--primary-lighter);
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: var(--primary-color);
            border-left: 4px solid var(--primary-light);
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #4a6b85;
        }
        
        .btn-secondary {
            background-color: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .btn-secondary:hover {
            background-color: #e9e9e9;
        }
        
        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .required-field::after {
            content: " *";
            color: var(--alert-color);
        }
        
        .password-strength {
            margin-top: 5px;
            font-size: 0.85rem;
        }
        
        .strength-weak {
            color: #d9534f;
        }
        
        .strength-medium {
            color: #f0ad4e;
        }
        
        .strength-strong {
            color: #5cb85c;
        }
        
        footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            font-size: 0.8rem;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            
            .form-group {
                flex: 1 0 100%;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Inclure le footer
    include 'header.php'; 
    ?>
    
    <div class="container">
        <main>
            <div class="breadcrumb">
                <a href="accueil.php">Accueil</a>
                <span>›</span>
                <a href="admin.php">Administration</a>
                <span>›</span>
                <strong>Création de compte</strong>
            </div>
            
            <h1 class="page-title">Création d'un nouveau compte utilisateur</h1>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <h2 class="card-title">Informations du compte</h2>
                
                <div class="form-info">
                    Veuillez remplir tous les champs obligatoires (*) pour créer un nouveau compte utilisateur.
                </div>
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom" class="required-field">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control" value="<?php echo isset($nom) ? htmlspecialchars($nom) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom" class="required-field">Prénom</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" value="<?php echo isset($prenom) ? htmlspecialchars($prenom) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="required-field">Nom d'utilisateur</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="required-field">Adresse e-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="required-field">Mot de passe</label>
                            <input type="password" id="password" name="password" class="form-control" required onkeyup="checkPasswordStrength()">
                            <div id="password-strength" class="password-strength"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="required-field">Confirmer le mot de passe</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="role" class="required-field">Rôle</label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">Sélectionnez un rôle</option>
                                <option value="medecin" <?php echo (isset($role) && $role === 'medecin') ? 'selected' : ''; ?>>Médecin</option>
                                <option value="assistant" <?php echo (isset($role) && $role === 'assistant') ? 'selected' : ''; ?>>Assistant médical</option>
                                <option value="administrateur" <?php echo (isset($role) && $role === 'administrateur') ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="specialite">Spécialité</label>
                            <select id="specialite" name="specialite" class="form-control">
                                <option value="">Sélectionnez une spécialité</option>
                                <option value="hematologie" <?php echo (isset($specialite) && $specialite === 'hematologie') ? 'selected' : ''; ?>>Hématologie</option>
                                <option value="oncologie" <?php echo (isset($specialite) && $specialite === 'oncologie') ? 'selected' : ''; ?>>Oncologie</option>
                                <option value="medecine_interne" <?php echo (isset($specialite) && $specialite === 'medecine_interne') ? 'selected' : ''; ?>>Médecine interne</option>
                                <option value="pediatrie" <?php echo (isset($specialite) && $specialite === 'pediatrie') ? 'selected' : ''; ?>>Pédiatrie</option>
                                <option value="autre" <?php echo (isset($specialite) && $specialite === 'autre') ? 'selected' : ''; ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <a href="admin.php" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer le compte</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php 
    // Inclure le footer
    include 'footer.php'; 
    ?>
    
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthIndicator = document.getElementById('password-strength');
            
            // Réinitialiser le texte
            strengthIndicator.textContent = '';
            
            // Si le champ est vide, ne rien afficher
            if (password.length === 0) {
                return;
            }
            
            // Définir les critères
            const hasLowerCase = /[a-z]/.test(password);
            const hasUpperCase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password);
            const hasMinLength = password.length >= 8;
            
            // Calculer le score
            let score = 0;
            if (hasLowerCase) score++;
            if (hasUpperCase) score++;
            if (hasNumber) score++;
            if (hasSpecialChar) score++;
            if (hasMinLength) score++;
            
            // Déterminer la force
            let strength = '';
            let strengthClass = '';
            
            if (score < 3) {
                strength = 'Faible';
                strengthClass = 'strength-weak';
            } else if (score < 5) {
                strength = 'Moyen';
                strengthClass = 'strength-medium';
            } else {
                strength = 'Fort';
                strengthClass = 'strength-strong';
            }
            
            // Afficher le résultat
            strengthIndicator.textContent = `Force du mot de passe: ${strength}`;
            strengthIndicator.className = `password-strength ${strengthClass}`;
        }
    </script>
</body>
</html>