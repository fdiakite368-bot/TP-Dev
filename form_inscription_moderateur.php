<?php
require_once 'connexion_db.php';
require_once 'verif_moderateur.php';
// 2. Requête pour récupérer les données
$sql = "SELECT id, livre_id, utilisateur_id, date_inscription FROM inscriptions_session ORDER BY date_inscription DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="dashboard.css">
    <meta charset="UTF-8">
    <title>Dashboard - Gestion des Inscriptions</title>
    <style>
       /* Conteneur principal */
.container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 1.5rem;
    background: #111827; /* --surface */
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

h2 {
    color: #e5e7eb;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    border-left: 4px solid #22d3ee;
    padding-left: 1rem;
}

/* Style du Tableau */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    color: #e5e7eb;
    overflow: hidden;
    border-radius: 8px;
}

/* En-tête (Thead) */
thead {
    background: #1f2937; /* --surface-light */
}

th {
    text-align: left;
    padding: 1rem;
    color: #22d3ee; /* --primary */
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid rgba(34, 211, 238, 0.2);
}

/* Lignes du corps (Tbody) */
td {
    padding: 1rem;
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    font-size: 0.95rem;
}

/* Effet de survol sur les lignes */
tbody tr:hover {
    background: rgba(34, 211, 238, 0.05);
    transition: background 0.2s ease;
}
    </style>
</head>
<body>

<div class="container">
    <h2>Tableau de bord des inscriptions</h2>

    <table>
        <thead>
            <tr>
                <th>Livre</th>
                <th>Nom Utilisateur</th>
                <th>Date d'inscription</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                // 3. Affichage des lignes
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td><span class='badge'>" . htmlspecialchars($row["livre_id"]) . "</span></td>";
                    echo "<td>" . htmlspecialchars($row["utilisateur_id"]) . "</td>";
                    // Formatage de la date en format français
                    $date = date('d/m/Y à H:i', strtotime($row["date_inscription"]));
                    echo "<td>" . $date . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Aucune donnée enregistrée pour le moment.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
<script src="dashboard.js"></script>
<?php
// 4. Fermeture de la connexion
mysqli_close($conn);
?>