<?php
require_once 'verif_session.php';
require_once 'connexion_db.php';

$sql = "SELECT lecture.id, lecture.statut,
               livres.titre AS livre,
               utilisateurs.nom AS utilisateur
        FROM lecture
        LEFT JOIN livres ON lecture.livre_id = livres.id
        LEFT JOIN utilisateurs ON lecture.utilisateur_id = utilisateurs.id";

$result = mysqli_query($conn, $sql);
?>
<link rel="stylesheet" href="dashboard.css">
<h1>Liste des lectures</h1>
<?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'modérateur'): ?>
    <a href="ajout_lecture.php" style="display: inline-block; margin-bottom: 20px;">Ajouter une lecture</a>
<?php endif; ?>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <div style="margin-bottom:20px; border:1px solid #ccc; padding:10px; width:300px;">
        
        <h3><?= $row['livre'] ?></h3>
        <p>Utilisateur : <?= $row['utilisateur'] ?></p>
        <p>Statut : <?= $row['statut'] ?></p>

        <a href="fiche_lecture.php?id=<?= $row['id'] ?>">Voir la fiche</a> 

        <?php if ($_SESSION['role'] === 'admin'|| $_SESSION['role'] === 'modérateur'): ?>
            | <a href="modifier_lecture.php?id=<?= $row['id'] ?>">Modifier</a> 
        

        <?php if ($_SESSION['role'] === 'admin'): ?>
            | <a href="supprimer_lecture.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer cette lecture ?')">Supprimer</a>
        <?php endif; ?> 
        <?php endif; ?> 
    </div>
<?php endwhile; ?>
<script src="dashboard.js"></script>
<style>
    /* --- Mise en page globale --- */
body {
    background-color: #0f172a; /* Fond sombre moderne */
    color: #e5e7eb;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    padding: 2rem;
}

h1 {
    color: #22d3ee;
    font-size: 2.5rem;
    border-bottom: 2px solid rgba(34, 211, 238, 0.2);
    padding-bottom: 10px;
    margin-bottom: 30px;
}

/* --- Bouton Ajouter --- */
a[href="ajout_lecture.php"] {
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a !important;
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 15px rgba(34, 211, 238, 0.3);
}

a[href="ajout_lecture.php"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(34, 211, 238, 0.4);
}

/* --- Conteneur des cartes (Grille) --- */
/* Pour que ça marche, je te conseille d'entourer ta boucle WHILE par un <div class="grid-container"> */
.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

/* --- Style des Cartes (Lecture) --- */
.lecture-card {
    background: #1e293b;
    border: 1px solid rgba(148, 163, 184, 0.1);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    width: auto !important; /* On annule ton width: 300px en dur */
}

.lecture-card:hover {
    transform: translateY(-5px);
    border-color: #22d3ee;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

.lecture-card h3 {
    margin: 0 0 15px 0;
    color: #f1f5f9;
    font-size: 1.3rem;
}

.lecture-card p {
    margin: 8px 0;
    color: #94a3b8;
    font-size: 0.95rem;
}

.lecture-card p strong {
    color: #22d3ee;
}

/* --- Liens et Actions --- */
.actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid rgba(148, 163, 184, 0.1);
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.actions a {
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    color: #22d3ee;
    transition: color 0.2s;
}

.actions a:hover {
    color: #f1f5f9;
}

/* Style spécifique pour Supprimer */
.actions a[href*="supprimer"] {
    color: #ef4444;
}

.actions a[href*="supprimer"]:hover {
    color: #fca5a5;
}

/* --- Statut Badge --- */
.statut-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(34, 211, 238, 0.1);
    color: #22d3ee;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}
</style>