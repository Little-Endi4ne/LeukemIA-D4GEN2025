<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Dans une application réelle, vous récupéreriez ces données depuis une base de données
// Ici, nous simulons des données de patients pour l'exemple
$patients_recents = [
    [
        'id' => 1,
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'age' => 67,
        'type_leucemie' => 'LMA',
        'date_diagnostic' => '2025-02-15',
        'derniere_consultation' => '2025-05-10',
        'estimation_survie' => '24 mois',
        'statut' => 'En traitement',
        'risque' => 'Modéré'
    ],
    [
        'id' => 2,
        'nom' => 'Martin',
        'prenom' => 'Sophie',
        'age' => 42,
        'type_leucemie' => 'LLC',
        'date_diagnostic' => '2025-03-22',
        'derniere_consultation' => '2025-05-12',
        'estimation_survie' => '60+ mois',
        'statut' => 'En rémission',
        'risque' => 'Faible'
    ],
    [
        'id' => 3,
        'nom' => 'Petit',
        'prenom' => 'Robert',
        'age' => 75,
        'type_leucemie' => 'LMA',
        'date_diagnostic' => '2025-01-05',
        'derniere_consultation' => '2025-05-05',
        'estimation_survie' => '12 mois',
        'statut' => 'En traitement',
        'risque' => 'Élevé'
    ],
    [
        'id' => 4,
        'nom' => 'Leroy',
        'prenom' => 'Marie',
        'age' => 38,
        'type_leucemie' => 'LLA',
        'date_diagnostic' => '2025-04-18',
        'derniere_consultation' => '2025-05-02',
        'estimation_survie' => '48 mois',
        'statut' => 'Nouveau diagnostic',
        'risque' => 'Modéré'
    ],
    [
        'id' => 5,
        'nom' => 'Moreau',
        'prenom' => 'Philippe',
        'age' => 55,
        'type_leucemie' => 'LMC',
        'date_diagnostic' => '2024-11-10',
        'derniere_consultation' => '2025-05-15',
        'estimation_survie' => '72+ mois',
        'statut' => 'En rémission',
        'risque' => 'Faible'
    ]
];

// Traitement de recherche
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
$filtre_type = isset($_GET['type_leucemie']) ? $_GET['type_leucemie'] : '';
$filtre_statut = isset($_GET['statut']) ? $_GET['statut'] : '';

// Filtrer les patients si recherche ou filtres
if (!empty($recherche) || !empty($filtre_type) || !empty($filtre_statut)) {
    $patients_filtres = [];
    
    foreach ($patients_recents as $patient) {
        $match_recherche = empty($recherche) || 
            stripos($patient['nom'], $recherche) !== false || 
            stripos($patient['prenom'], $recherche) !== false;
            
        $match_type = empty($filtre_type) || $patient['type_leucemie'] === $filtre_type;
        $match_statut = empty($filtre_statut) || $patient['statut'] === $filtre_statut;
        
        if ($match_recherche && $match_type && $match_statut) {
            $patients_filtres[] = $patient;
        }
    }
    
    $patients_affiches = $patients_filtres;
} else {
    $patients_affiches = $patients_recents;
}

// Trier les patients par date de consultation décroissante (les plus récents d'abord)
usort($patients_affiches, function($a, $b) {
    return strtotime($b['derniere_consultation']) - strtotime($a['derniere_consultation']);
});

// Obtenir les types de leucémie uniques pour le filtre
$types_leucemie = [];
$statuts = [];
foreach ($patients_recents as $patient) {
    $types_leucemie[$patient['type_leucemie']] = $patient['type_leucemie'];
    $statuts[$patient['statut']] = $patient['statut'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeukemIA - Patients Récents</title>
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
        
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            align-items: flex-end;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 0.9rem;
        }
        
        .filter-group select, .filter-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--primary-light);
            border-radius: 4px;
            font-size: 0.95rem;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
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
        
        .patient-row {
            cursor: pointer;
        }
        
        .status-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-en-traitement {
            background-color: var(--primary-lighter);
            color: var(--primary-color);
        }
        
        .status-remission {
            background-color: var(--positive-light);
            color: #2a5042;
        }
        
        .status-nouveau {
            background-color: var(--alert-light);
            color: #9a4836;
        }
        
        .risk-high {
            color: #d9534f;
        }
        
        .risk-medium {
            color: #f0ad4e;
        }
        
        .risk-low {
            color: #5cb85c;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }
        
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            background-color: white;
            color: var(--primary-color);
            text-decoration: none;
            border: 1px solid var(--primary-light);
        }
        
        .pagination a:hover, .pagination a.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .no-results {
            text-align: center;
            padding: 30px;
            color: #666;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        .btn-view {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-edit {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        .btn-delete {
            background-color: var(--alert-light);
            color: #9a4836;
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
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                width: 100%;
            }
            
            table {
                font-size: 0.85rem;
            }
            
            th, td {
                padding: 8px 10px;
            }
            
            .action-buttons {
                flex-direction: column;
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
                <strong>Patients récents</strong>
            </div>
            
            <h1 class="page-title">Patients récemment consultés</h1>
            
            <div class="card">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                    <div class="filters">
                        <div class="filter-group">
                            <label for="recherche">Recherche par nom</label>
                            <input type="text" id="recherche" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>" placeholder="Nom ou prénom du patient">
                        </div>
                        
                        <div class="filter-group">
                            <label for="type_leucemie">Type de leucémie</label>
                            <select id="type_leucemie" name="type_leucemie">
                                <option value="">Tous les types</option>
                                <?php foreach ($types_leucemie as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($filtre_type === $type) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="statut">Statut</label>
                            <select id="statut" name="statut">
                                <option value="">Tous les statuts</option>
                                <?php foreach ($statuts as $statut): ?>
                                    <option value="<?php echo htmlspecialchars($statut); ?>" <?php echo ($filtre_statut === $statut) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($statut); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-buttons">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </div>
                </form>
                
                <div class="table-container">
                    <?php if (count($patients_affiches) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Âge</th>
                                    <th>Type</th>
                                    <th>Date diagnostic</th>
                                    <th>Dernière visite</th>
                                    <th>Statut</th>
                                    <th>Estimation</th>
                                    <th>Risque</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($patients_affiches as $patient): ?>
                                    <tr class="patient-row">
                                        <td>
                                            <strong><?php echo htmlspecialchars($patient['nom']); ?></strong> <?php echo htmlspecialchars($patient['prenom']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($patient['age']); ?> ans</td>
                                        <td><?php echo htmlspecialchars($patient['type_leucemie']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($patient['date_diagnostic'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($patient['derniere_consultation'])); ?></td>
                                        <td>
                                            <?php 
                                                $status_class = '';
                                                switch ($patient['statut']) {
                                                    case 'En traitement':
                                                        $status_class = 'status-en-traitement';
                                                        break;
                                                    case 'En rémission':
                                                        $status_class = 'status-remission';
                                                        break;
                                                    case 'Nouveau diagnostic':
                                                        $status_class = 'status-nouveau';
                                                        break;
                                                }
                                            ?>
                                            <span class="status-pill <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($patient['statut']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($patient['estimation_survie']); ?></td>
                                        <td>
                                            <?php 
                                                $risk_class = '';
                                                switch ($patient['risque']) {
                                                    case 'Élevé':
                                                        $risk_class = 'risk-high';
                                                        break;
                                                    case 'Modéré':
                                                        $risk_class = 'risk-medium';
                                                        break;
                                                    case 'Faible':
                                                        $risk_class = 'risk-low';
                                                        break;
                                                }
                                            ?>
                                            <span class="<?php echo $risk_class; ?>">
                                                <?php echo htmlspecialchars($patient['risque']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="patient-details.php?id=<?php echo $patient['id']; ?>" class="btn btn-sm btn-view">Voir</a>
                                                <a href="patient-edit.php?id=<?php echo $patient['id']; ?>" class="btn btn-sm btn-edit">Modifier</a>
                                                <a href="#" class="btn btn-sm btn-delete" onclick="confirmDelete(<?php echo $patient['id']; ?>)">Supprimer</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="pagination">
                            <a href="#" class="active">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#">›</a>
                        </div>
                    <?php else: ?>
                        <div class="no-results">
                            <p>Aucun patient ne correspond à vos critères de recherche.</p>
                            <p>Veuillez modifier vos filtres ou créer un nouveau patient.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="text-align: right; margin-top: 20px;">
                    <a href="nouveau-patient.php" class="btn btn-primary">Nouveau patient</a>
                </div>
            </div>
        </main>
    </div>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 LeukemIA - Outil d'aide à la décision médicale pour les patients atteints de leucémie</p>
        </div>
    </footer>
    
    <script>
        function confirmDelete(patientId) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce patient ? Cette action est irréversible.")) {
                // Dans une application réelle, vous redirigeriez vers une page de suppression
                // ou vous feriez une requête AJAX pour supprimer le patient
                alert("Suppression du patient #" + patientId + " (simulation)");
            }
        }
    </script>
</body>
</html>