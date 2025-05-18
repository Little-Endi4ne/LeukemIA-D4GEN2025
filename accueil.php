<?php
// Définir le titre de la page
$page_title = "Accueil";

// Inclure le header
require_once 'header.php';

// Script pour créer le fichier de déconnexion si nécessaire
$logout_content = '<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
?>';

if (!file_exists('logout.php')) {
    file_put_contents('logout.php', $logout_content);
}
?>

<div class="container">
    <main>
        <section class="welcome-section">
            <h2>Bienvenue dans LeukemIA, Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>Cet outil vous aide à estimer la durée de vie pour les patients atteints de leucémie en fonction de divers paramètres cliniques et biologiques.</p>
        </section>
        
        <section class="info-banner">
            <i>⚠️</i>
            <div>
                <strong>Information importante :</strong> Les estimations fournies sont des indications basées sur des données statistiques et ne doivent pas être considérées comme des prédictions exactes. Utilisez votre jugement clinique.
            </div>
        </section>
        
        <section class="dashboard">
            <div class="card">
                <h3 class="card-title">Nouveau patient</h3>
                <div class="card-content">
                    <p>Enregistrez un nouveau patient et commencez une nouvelle estimation de durée de vie.</p>
                </div>
                <div class="card-action">
                    <a href="creation-compte.php" class="btn btn-primary">Créer</a>
                </div>
            </div>
            
            <div class="card">
                <h3 class="card-title">Patients récents</h3>
                <div class="card-content">
                    <p>Accédez aux dossiers des patients récemment consultés et à leurs estimations.</p>
                </div>
                <div class="card-action">
                    <a href="patient-recent.php" class="btn btn-primary">Consulter</a>
                </div>
            </div>
            
            <div class="card">
                <h3 class="card-title">Statistiques</h3>
                <div class="card-content">
                    <p>Visualisez les statistiques globales et les tendances pour tous vos patients.</p>
                </div>
                <div class="card-action">
                    <a href="statistiques.php" class="btn btn-primary">Analyser</a>
                </div>
            </div>
        </section>
        
        <section class="dashboard">
            <div class="card">
                <h3 class="card-title">Guide d'utilisation</h3>
                <div class="card-content">
                    <p>Consultez le guide d'utilisation pour comprendre comment utiliser efficacement cet outil.</p>
                </div>
                <div class="card-action">
                    <a href="guide.php" class="btn btn-positive">Voir le guide</a>
                </div>
            </div>
            
            <div class="card">
                <h3 class="card-title">Mise à jour des données</h3>
                <div class="card-content">
                    <p>Les dernières études et données statistiques ont été intégrées le 10/05/2025.</p>
                </div>
                <div class="card-action">
                    <a href="mises-a-jour.php" class="btn btn-positive">Détails</a>
                </div>
            </div>
        </section>
    </main>
</div>

<style>
    /* Styles spécifiques à la page d'accueil */
    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-title {
        color: var(--primary-color);
        font-size: 1.2rem;
        margin-bottom: 15px;
        border-bottom: 2px solid var(--primary-light);
        padding-bottom: 10px;
    }
    
    .card-content {
        font-size: 0.95rem;
    }
    
    .card-action {
        margin-top: 20px;
        text-align: right;
    }
    
    .btn-positive {
        background-color: var(--positive-color);
        color: white;
    }
    
    .btn-positive:hover {
        background-color: #97b8a6;
    }
    
    .welcome-section {
        background-color: var(--positive-light);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 5px solid var(--positive-color);
    }
    
    .welcome-section h2 {
        color: #2a5042;
        margin-bottom: 10px;
    }
    
    .welcome-section p {
        color: #2a5042;
    }
    
    .info-banner {
        background-color: var(--alert-light);
        color: #9a4836;
        padding: 15px;
        border-radius: 6px;
        margin: 20px 0;
        display: flex;
        align-items: center;
        border-left: 5px solid var(--alert-color);
    }
    
    .info-banner i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
</style>

<?php 
// Inclure le footer
include 'footer.php'; 
?>