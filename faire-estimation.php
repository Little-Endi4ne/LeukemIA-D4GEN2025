<?php
// Définir le titre de la page
$page_title = "Estimation IA";

// Inclure le header
require_once 'header.php';

// Générer un ID patient aléatoire
$patient_id = 'LKIA-' . date('Y') . '-' . mt_rand(10000, 99999);

// Simuler si l'estimation a été calculée
$estimation_done = isset($_POST['analyser']) || isset($_GET['demo']);

// Générer des résultats aléatoires si l'estimation est faite
$resultat = [];
if ($estimation_done) {
    $types_leucemie = ['LMA', 'LLA', 'LMC', 'LLC'];
    $type_leucemie = $types_leucemie[array_rand($types_leucemie)];
    
    $niveaux_risque = ['Faible', 'Modéré', 'Élevé'];
    $niveau_risque = $niveaux_risque[array_rand($niveaux_risque)];
    
    $duree_vie = mt_rand(12, 120);
    if ($niveau_risque == 'Élevé') {
        $duree_vie = round($duree_vie * 0.6);
    } else if ($niveau_risque == 'Faible') {
        $duree_vie = round($duree_vie * 1.5);
    }
    
    $traitements = [
        'LMA' => ['Chimiothérapie d\'induction', 'Consolidation haute-dose', 'Greffe de moelle'],
        'LLA' => ['Protocole GRAALL', 'Thérapie ciblée', 'Prophylaxie SNC'],
        'LMC' => ['Inhibiteur tyrosine kinase', 'Suivi moléculaire régulier'],
        'LLC' => ['Surveillance active', 'Immunochimiothérapie', 'Inhibiteurs de BTK']
    ];
}
?>

<div class="container">
    <main>
        <div class="breadcrumb">
            <a href="accueil.php">Accueil</a>
            <span>›</span>
            <strong>Estimation IA</strong>
        </div>
        
        <h1 class="page-title">Analyse Prédictive par Intelligence Artificielle</h1>
        
        <?php if (!$estimation_done): ?>
        <!-- Page d'upload -->
        <div class="card">
            <div class="header-banner">
                <h2>Téléchargement des analyses du patient</h2>
                <p>Notre IA analysera les documents pour générer une estimation précise</p>
            </div>
            
            <div class="patient-info">
                <div class="info-item">
                    <span class="label">ID Patient:</span>
                    <span class="value"><?php echo $patient_id; ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Date:</span>
                    <span class="value"><?php echo date('d/m/Y'); ?></span>
                </div>
            </div>
            
            <form action="?demo=1" method="post" class="upload-form">
                <div class="upload-area" id="dropArea">
                    <div class="upload-icon">
                        <i class="upload-svg"></i>
                    </div>
                    <div class="upload-text">
                        <h3>Déposez vos fichiers ici</h3>
                        <p>ou cliquez pour parcourir</p>
                    </div>
                    <input type="file" id="fileInput" multiple class="file-input">
                </div>
                
                <div class="file-list">
                    <div class="file-item">
                        <div class="file-name">Hémogramme_Patient.pdf</div>
                        <div class="file-size">2.3 MB</div>
                        <button type="button" class="btn-icon">×</button>
                    </div>
                    <div class="file-item">
                        <div class="file-name">Myélogramme_24032025.pdf</div>
                        <div class="file-size">4.1 MB</div>
                        <button type="button" class="btn-icon">×</button>
                    </div>
                </div>
                
                <div class="info-box">
                    <p><strong>Formats acceptés:</strong> PDF, JPG, PNG (max 20 Mo)</p>
                    <p><strong>Documents requis:</strong> Hémogramme complet et analyse de moelle osseuse</p>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="analyser" class="btn btn-primary btn-analyze">Lancer l'analyse IA</button>
                </div>
            </form>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon ai-icon"></div>
                <h3>IA de pointe</h3>
                <p>Algorithme entraîné sur plus de 50 000 dossiers médicaux</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon precision-icon"></div>
                <h3>Haute précision</h3>
                <p>Précision de 73% sur les estimations à 60 mois</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon secure-icon"></div>
                <h3>Sécurisé</h3>
                <p>Toutes les données sont traitées conformément au RGPD</p>
            </div>
        </div>
        
        <?php else: ?>
        <!-- Page de résultats -->
        <div class="results-panel">
            <div class="results-header">
                <div class="patient-data">
                    <div><strong>ID Patient:</strong> <?php echo $patient_id; ?></div>
                    <div><strong>Type:</strong> <?php echo $type_leucemie; ?></div>
                    <div><strong>Date d'analyse:</strong> <?php echo date('d/m/Y'); ?></div>
                </div>
            </div>
            
            <div class="results-content">
                <div class="estimation-box">
                    <h2>Estimation de durée de vie</h2>
                    
                    <div class="estimation-display">
                        <div class="estimation-value">
                            <span class="number"><?php echo $duree_vie; ?></span>
                            <span class="unit">mois</span>
                        </div>
                        
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo min(100, $duree_vie/2); ?>%;"></div>
                        </div>
                        
                        <div class="risk-level <?php echo strtolower($niveau_risque); ?>">
                            Niveau de risque: <strong><?php echo $niveau_risque; ?></strong>
                        </div>
                    </div>
                    
                    <div class="disclaimer">
                        Cette estimation est basée sur des données statistiques et ne constitue pas une prédiction exacte.
                    </div>
                </div>
                
                <div class="treatment-box">
                    <h2>Traitements recommandés</h2>
                    
                    <ul class="treatment-list">
                        <?php foreach ($traitements[$type_leucemie] as $traitement): ?>
                            <li><?php echo $traitement; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="note">
                        Ces recommandations sont générées automatiquement et ne remplacent pas l'avis médical d'un spécialiste.
                    </div>
                </div>
            </div>
            
            <div class="results-actions">
                <a href="faire-estimation.php" class="btn btn-secondary">Nouvelle analyse</a>
                <button type="button" class="btn btn-primary" onclick="window.print()">Imprimer</button>
                <button type="button" class="btn btn-primary">Exporter en PDF</button>
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>

<style>
    /* Styles pour la page d'estimation */
    .header-banner {
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
        border-radius: 8px 8px 0 0;
        margin: -20px -20px 20px;
    }
    
    .header-banner h2 {
        margin: 0 0 5px;
        color: white;
        border: none;
    }
    
    .header-banner p {
        margin: 0;
        opacity: 0.8;
    }
    
    .patient-info {
        background-color: var(--primary-lighter);
        padding: 12px 20px;
        border-radius: 6px;
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .label {
        font-weight: 500;
        color: var(--primary-color);
    }
    
    .upload-form {
        margin-bottom: 20px;
    }
    
    .upload-area {
        border: 2px dashed var(--primary-light);
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 20px;
        position: relative;
        cursor: pointer;
    }
    
    .upload-area:hover {
        background-color: var(--primary-lighter);
    }
    
    .upload-svg {
        display: inline-block;
        width: 48px;
        height: 48px;
        margin-bottom: 15px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235A7D9A' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4'%3E%3C/path%3E%3Cpolyline points='17 8 12 3 7 8'%3E%3C/polyline%3E%3Cline x1='12' y1='3' x2='12' y2='15'%3E%3C/line%3E%3C/svg%3E");
        background-size: contain;
    }
    
    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .file-list {
        margin-bottom: 20px;
    }
    
    .file-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 10px;
    }
    
    .file-name {
        flex: 1;
        font-weight: 500;
    }
    
    .file-size {
        color: #666;
        margin-right: 15px;
        font-size: 0.9rem;
    }
    
    .btn-icon {
        background: none;
        border: none;
        font-size: 1.2rem;
        line-height: 1;
        padding: 0 5px;
        cursor: pointer;
        color: #999;
    }
    
    .btn-icon:hover {
        color: #d9534f;
    }
    
    .info-box {
        background-color: var(--primary-lighter);
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    
    .info-box p {
        margin: 0 0 5px;
    }
    
    .info-box p:last-child {
        margin-bottom: 0;
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
    }
    
    .btn-analyze {
        padding: 10px 20px;
        font-size: 1.1rem;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .feature-item {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        text-align: center;
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 15px;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .ai-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235A7D9A' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='16 18 22 12 16 6'%3E%3C/polyline%3E%3Cpolyline points='8 6 2 12 8 18'%3E%3C/polyline%3E%3Cline x1='15' y1='12' x2='9' y2='12'%3E%3C/line%3E%3C/svg%3E");
    }
    
    .precision-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235A7D9A' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpolyline points='12 6 12 12 16 14'%3E%3C/polyline%3E%3C/svg%3E");
    }
    
    .secure-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235A7D9A' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='11' width='18' height='11' rx='2' ry='2'%3E%3C/rect%3E%3Cpath d='M7 11V7a5 5 0 0 1 10 0v4'%3E%3C/path%3E%3C/svg%3E");
    }
    
    .feature-item h3 {
        margin: 0 0 10px;
        color: var(--primary-color);
    }
    
    .feature-item p {
        margin: 0;
        color: #666;
    }
    
    /* Styles pour les résultats */
    .results-panel {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .results-header {
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
    }
    
    .patient-data {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .results-content {
        padding: 30px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    
    .estimation-box, .treatment-box {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }
    
    .estimation-box h2, .treatment-box h2 {
        color: var(--primary-color);
        margin: 0 0 20px;
        font-size: 1.3rem;
        border-bottom: 2px solid var(--primary-light);
        padding-bottom: 10px;
    }
    
    .estimation-display {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .estimation-value {
        margin-bottom: 15px;
    }
    
    .number {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .unit {
        font-size: 1.5rem;
        color: #666;
    }
    
    .progress-bar {
        height: 8px;
        background-color: #ddd;
        border-radius: 4px;
        margin-bottom: 15px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background-color: var(--primary-color);
    }
    
    .risk-level {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    
    .risk-level.faible {
        background-color: var(--positive-light);
        color: #2a5042;
    }
    
    .risk-level.modéré {
        background-color: #FFF3CD;
        color: #856404;
    }
    
    .risk-level.élevé {
        background-color: var(--alert-light);
        color: #9a4836;
    }
    
    .disclaimer, .note {
        font-size: 0.85rem;
        color: #666;
        font-style: italic;
    }
    
    .treatment-list {
        list-style-type: none;
        padding: 0;
        margin: 0 0 20px;
    }
    
    .treatment-list li {
        padding: 12px 15px;
        background-color: white;
        margin-bottom: 10px;
        border-radius: 6px;
        border-left: 4px solid var(--primary-color);
    }
    
    .results-actions {
        padding: 20px;
        background-color: #f8f9fa;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    @media (max-width: 768px) {
        .results-content {
            grid-template-columns: 1fr;
        }
        
        .patient-data {
            flex-direction: column;
            gap: 10px;
        }
        
        .results-actions {
            flex-direction: column;
        }
        
        .results-actions .btn {
            width: 100%;
        }
    }
    
    @media print {
        header, footer, .breadcrumb, .results-actions {
            display: none;
        }
        
        body {
            background-color: white;
        }
        
        .results-panel {
            box-shadow: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation pour la zone de glisser-déposer
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        
        if (dropArea && fileInput) {
            dropArea.addEventListener('click', function() {
                fileInput.click();
            });
            
            dropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropArea.classList.add('active');
            });
            
            dropArea.addEventListener('dragleave', function() {
                dropArea.classList.remove('active');
            });
            
            dropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                dropArea.classList.remove('active');
                // Dans une implémentation réelle, vous traiteriez les fichiers ici
                alert('Fichiers déposés! Dans une version complète, ils seraient téléchargés.');
            });
        }
    });
</script>

<?php 
// Inclure le footer
include 'footer.php';
?>