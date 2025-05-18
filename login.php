<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: accueil.php");
    exit;
}

// Variables pour stocker les messages
$error_message = "";
$success_message = "";

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Identifiants attendus (à remplacer par une base de données dans une application réelle)
    $expected_username = "toubib";
    $expected_password = "mdp";

    // Récupérer les données du formulaire
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifier les identifiants
    if ($username === $expected_username && $password === $expected_password) {
        // Authentification réussie
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        
        // Rediriger vers la page d'accueil
        header("Location: accueil.php");
        exit;
    } else {
        $error_message = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeukemIA - Connexion</title>
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            overflow: hidden;
        }
        
        .login-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .login-header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
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
            padding: 12px;
            border: 1px solid var(--primary-light);
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(90, 125, 154, 0.2);
        }
        
        .btn {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #4a6b85;
        }
        
        .alert {
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: var(--alert-light);
            color: #9a4836;
            border-left: 4px solid var(--alert-color);
        }
        
        .alert-success {
            background-color: var(--positive-light);
            color: #2a5042;
            border-left: 4px solid var(--positive-color);
        }
        
        .version-info {
            text-align: center;
            font-size: 0.8rem;
            color: #888;
            margin-top: 20px;
        }
        
        .app-icon {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .app-icon span {
            font-size: 3rem;
            color: white;
        }
        
        .secure-note {
            text-align: center;
            font-size: 0.8rem;
            color: #888;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .secure-note svg {
            margin-right: 5px;
            height: 14px;
            width: 14px;
        }
        
        @media (max-width: 500px) {
            .login-container {
                width: 100%;
                max-width: 100%;
                border-radius: 0;
                height: 100vh;
            }
            
            .login-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="app-icon">
                <span>🩺</span>
            </div>
            <h1>LeukemIA</h1>
            <p>Estimation de durée de vie pour patients leucémiques</p>
        </div>
        
        <div class="login-form">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Identifiant</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn">Se connecter</button>
            </form>
            
            <div class="secure-note">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Connexion sécurisée
            </div>
            
            <div class="version-info">
                LeukemIA v1.0.0 &copy; 2025
            </div>
        </div>
    </div>
</body>
</html>