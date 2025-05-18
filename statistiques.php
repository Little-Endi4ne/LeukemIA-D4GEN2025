<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Dans une application réelle, ces données viendraient d'une base de données
// Ici, nous simulons les données pour l'exemple

// Répartition des types de leucémie
$repartition_types = [
    'LMA' => 62,
    'LLA' => 8,
    'LMC' => 25,
    'LLC' => 7
];

// Répartition par tranche d'âge
$repartition_age = [
    '0-18' => 40,
    '19-40' => 5,
    '41-60' => 9,
    '61-75' => 67,
    '76+' => 12
];

// Taux de survie par type (en mois)
$taux_survie = [
    'LMA' => [
        'median' => 35,
        'min' => 2,
        'max' => 120
    ],
    'LLA' => [
        'median' => 36,
        'min' => 18,
        'max' => 72
    ],
    'LMC' => [
        'median' => 84,
        'min' => 60,
        'max' => 120
    ],
    'LLC' => [
        'median' => 96,
        'min' => 72,
        'max' => 144
    ]
];

// Répartition des patients par statut
$statuts_patients = [
    'En traitement' => 46,
    'En rémission' => 32,
    'Surveillance post-traitement' => 15,
    'Nouveau diagnostic' => 12
];

// Répartition des niveaux de risque
$niveaux_risque = [
    'Faible' => 25,
    'Modéré' => 45,
    'Élevé' => 22
];

// Évolution sur 12 mois (nouveaux patients par mois)
$evolution_12mois = [
    'Mai 2024' => 8,
    'Juin 2024' => 5,
    'Juillet 2024' => 7,
    'Août 2024' => 4,
    'Septembre 2024' => 6,
    'Octobre 2024' => 9,
    'Novembre 2024' => 10,
    'Décembre 2024' => 8,
    'Janvier 2025' => 12,
    'Février 2025' => 11,
    'Mars 2025' => 9,
    'Avril 2025' => 7,
    'Mai 2025' => 10
];

// Calcul des statistiques globales
$total_patients = array_sum($repartition_types);
$moyenne_age = 58; // Valeur simulée
$ratio_sexe = 1.2; // Hommes/Femmes, valeur simulée

// Période d'analyse
$periode = isset($_GET['periode']) ? $_GET['periode'] : '12mois';
$periodes_disponibles = [
    '3mois' => '3 derniers mois',
    '6mois' => '6 derniers mois',
    '12mois' => '12 derniers mois',
    'total' => 'Toutes les données'
];

// Informations sur les traitements
$traitements = [
    [
        'traitement' => 'Chimiothérapie standard',
        'patients' => 58,
        'taux_reponse' => '65%',
        'survie_mediane' => '28 mois'
    ],
    [
        'traitement' => 'Thérapie ciblée',
        'patients' => 32,
        'taux_reponse' => '72%',
        'survie_mediane' => '42 mois'
    ],
    [
        'traitement' => 'Immunothérapie',
        'patients' => 15,
        'taux_reponse' => '78%',
        'survie_mediane' => '36 mois'
    ],
    [
        'traitement' => 'Greffe de moelle osseuse',
        'patients' => 20,
        'taux_reponse' => '85%',
        'survie_mediane' => '60+ mois'
    ],
    [
        'traitement' => 'Combinaison de traitements',
        'patients' => 25,
        'taux_reponse' => '70%',
        'survie_mediane' => '45 mois'
    ]
];

// Convertir les données en format JSON pour les graphiques JavaScript
$json_types = json_encode($repartition_types);
$json_age = json_encode($repartition_age);
$json_statuts = json_encode($statuts_patients);
$json_risque = json_encode($niveaux_risque);
$json_evolution = json_encode($evolution_12mois);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeukemIA - Statistiques</title>
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
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .card-title {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--primary-light);
            padding-bottom: 10px;
        }
        
        .stats-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .periode-selector {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .periode-selector label {
            font-weight: 500;
            color: #555;
        }
        
        .periode-selector select {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid var(--primary-light);
            background-color: white;
        }
        
        .export-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: var(--primary-lighter);
            border-radius: 8px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 5px 0;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #555;
        }
        
        .charts-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .chart-container {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            height: 300px;
            position: relative;
        }
        
        .chart-title {
            font-size: 1rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .chart {
            width: 100%;
            height: calc(100% - 30px);
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--primary-lighter);
        }
        
        th {
            background-color: var(--primary-light);
            color: var(--primary-color);
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        tbody tr:hover {
            background-color: var(--primary-lighter);
        }
        
        .data-info {
            font-size: 0.85rem;
            color: #666;
            text-align: right;
            margin-top: 5px;
            font-style: italic;
        }
        
        .trend-up {
            color: #5cb85c;
        }
        
        .trend-down {
            color: #d9534f;
        }
        
        footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            font-size: 0.8rem;
        }
        
        @media (max-width: 992px) {
            .charts-row {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-controls {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <!-- Inclusion de la bibliothèque Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <strong>Statistiques</strong>
            </div>
            
            <h1 class="page-title">Statistiques et analyses</h1>
            
            <div class="card">
                <div class="stats-controls">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="periode-selector">
                        <label for="periode">Période d'analyse :</label>
                        <select id="periode" name="periode" onchange="this.form.submit()">
                            <?php foreach ($periodes_disponibles as $key => $label): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($periode === $key) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    
                    <div class="export-buttons">
                        <button class="btn btn-secondary" onclick="exportPDF()">
                            Exporter en PDF
                        </button>
                        <button class="btn btn-secondary" onclick="exportCSV()">
                            Exporter en CSV
                        </button>
                    </div>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Nombre total de patients</div>
                        <div class="stat-value"><?php echo $total_patients; ?></div>
                        <div class="stat-trend">+12% <span class="trend-up">↑</span></div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">Âge moyen</div>
                        <div class="stat-value"><?php echo $moyenne_age; ?> ans</div>
                        <div class="stat-trend">-2 ans <span class="trend-down">↓</span></div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">Ratio Hommes/Femmes</div>
                        <div class="stat-value"><?php echo $ratio_sexe; ?></div>
                        <div class="stat-trend">Stable <span>→</span></div>
                    </div>
                </div>
                
                <div class="charts-row">
                    <div class="chart-container">
                        <div class="chart-title">Répartition par type de leucémie</div>
                        <canvas id="typesChart" class="chart"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-title">Répartition par âge</div>
                        <canvas id="ageChart" class="chart"></canvas>
                    </div>
                </div>
                
                <div class="charts-row">
                    <div class="chart-container">
                        <div class="chart-title">Statut des patients</div>
                        <canvas id="statutChart" class="chart"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-title">Niveau de risque</div>
                        <canvas id="risqueChart" class="chart"></canvas>
                    </div>
                </div>
                
                <div class="chart-container" style="height: 350px;">
                    <div class="chart-title">Évolution du nombre de nouveaux patients (12 derniers mois)</div>
                    <canvas id="evolutionChart" class="chart"></canvas>
                </div>
                
                <div class="data-info">
                    Données mises à jour le 15/05/2025
                </div>
            </div>
            
            <div class="card">
                <h2 class="card-title">Survie médiane par type de leucémie</h2>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Type de leucémie</th>
                                <th>Survie médiane</th>
                                <th>Minimum</th>
                                <th>Maximum</th>
                                <th>Nombre de patients</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($taux_survie as $type => $donnees): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($type); ?></td>
                                    <td><?php echo $donnees['median']; ?> mois</td>
                                    <td><?php echo $donnees['min']; ?> mois</td>
                                    <td><?php echo $donnees['max']; ?> mois</td>
                                    <td><?php echo $repartition_types[$type]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <h2 class="card-title">Efficacité des traitements</h2>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Traitement</th>
                                <th>Nombre de patients</th>
                                <th>Taux de réponse</th>
                                <th>Survie médiane</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($traitements as $traitement): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($traitement['traitement']); ?></td>
                                    <td><?php echo $traitement['patients']; ?></td>
                                    <td><?php echo $traitement['taux_reponse']; ?></td>
                                    <td><?php echo $traitement['survie_mediane']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="data-info">
                    Note: Les données de survie sont basées sur des estimations et peuvent varier en fonction des caractéristiques individuelles des patients.
                </div>
            </div>
        </main>
    </div>
    
    <?php 
    // Inclure le footer
    include 'footer.php'; 
    ?>
    
    <script>
        // Données provenant du PHP
        const typesData = <?php echo $json_types; ?>;
        const ageData = <?php echo $json_age; ?>;
        const statutsData = <?php echo $json_statuts; ?>;
        const risqueData = <?php echo $json_risque; ?>;
        const evolutionData = <?php echo $json_evolution; ?>;
        
        // Configuration des couleurs pour les graphiques
        const colors = {
            primary: '#5A7D9A',
            primaryLight: '#B0C4D8',
            positive: '#A8CBB7',
            alert: '#E79C8B',
            chartColors: [
                '#5A7D9A', '#B0C4D8', '#A8CBB7', '#CFE6D3', '#E79C8B', '#F5C6A5'
            ]
        };
        
        // Options communes pour les graphiques
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: {
                            family: "'Segoe UI', sans-serif",
                            size: 12
                        },
                        padding: 15
                    }
                }
            }
        };
        
        // Graphique des types de leucémie
        const typesChart = new Chart(
            document.getElementById('typesChart'),
            {
                type: 'pie',
                data: {
                    labels: Object.keys(typesData),
                    datasets: [{
                        data: Object.values(typesData),
                        backgroundColor: colors.chartColors,
                        borderWidth: 1
                    }]
                },
                options: commonOptions
            }
        );
        
        // Graphique des tranches d'âge
        const ageChart = new Chart(
            document.getElementById('ageChart'),
            {
                type: 'bar',
                data: {
                    labels: Object.keys(ageData),
                    datasets: [{
                        label: 'Nombre de patients',
                        data: Object.values(ageData),
                        backgroundColor: colors.primary,
                        borderColor: colors.primary,
                        borderWidth: 1
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );
        
        // Graphique des statuts
        const statutChart = new Chart(
            document.getElementById('statutChart'),
            {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statutsData),
                    datasets: [{
                        data: Object.values(statutsData),
                        backgroundColor: colors.chartColors,
                        borderWidth: 1
                    }]
                },
                options: commonOptions
            }
        );
        
        // Graphique des niveaux de risque
        const risqueChart = new Chart(
            document.getElementById('risqueChart'),
            {
                type: 'pie',
                data: {
                    labels: Object.keys(risqueData),
                    datasets: [{
                        data: Object.values(risqueData),
                        backgroundColor: [
                            colors.positive,
                            colors.primaryLight,
                            colors.alert
                        ],
                        borderWidth: 1
                    }]
                },
                options: commonOptions
            }
        );
        
        // Graphique de l'évolution sur 12 mois
        const evolutionChart = new Chart(
            document.getElementById('evolutionChart'),
            {
                type: 'line',
                data: {
                    labels: Object.keys(evolutionData),
                    datasets: [{
                        label: 'Nouveaux patients',
                        data: Object.values(evolutionData),
                        backgroundColor: 'rgba(90, 125, 154, 0.2)',
                        borderColor: colors.primary,
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );
        
        // Fonctions d'export (simulations)
        function exportPDF() {
            alert("Export PDF en cours... Cette fonctionnalité serait implémentée avec une bibliothèque comme jsPDF dans une application réelle.");
        }
        
        function exportCSV() {
            alert("Export CSV en cours... Cette fonctionnalité serait implémentée avec une génération de fichier CSV dans une application réelle.");
        }
    </script>
</body>
</html>