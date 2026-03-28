<?php
require_once 'verif_session.php'; // Vérifie que l'utilisateur est connecté

// Seul l'admin peut modifier un livre
if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

require_once 'connexion_db.php';

// Vérification de l'ID
if (!isset($_GET['id'])) {
    echo "Aucun livre sélectionné.";
    exit;
}

$id = intval($_GET['id']);

// Récupération du livre
$sql = "SELECT * FROM livres WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$livres = mysqli_fetch_assoc($result);

if (!$livres) {
    echo "Livre introuvable.";
    exit;
}
?>
<link rel="stylesheet" href="dashboard.css">

<h2>Modifier le livre</h2>

<form action="traitement_modifier.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $livres['id'] ?>">

    <label>Titre :</label>
    <input type="text" name="titre" value="<?= $livres['titre'] ?>"><br>

    <label>Auteur :</label>
    <select name="auteur_id">
        <?php
        $auteurs = mysqli_query($conn, "SELECT * FROM auteurs");
        while ($a = mysqli_fetch_assoc($auteurs)) {
            $selected = ($a['id'] == $livres['auteur_id']) ? "selected" : "";
            echo "<option value='{$a['id']}' $selected>{$a['prenom']} {$a['nom']}</option>";
        }
        ?>
    </select><br>

    <label>Nombre de pages :</label>
    <input type="number" name="nb_pages" value="<?= $livres['nb_pages'] ?>"><br>

    <label>Genre :</label>
    <input type="text" name="genre" value="<?= $livres['genre'] ?>"><br>

    <label>Changer la couverture :</label>
    <input type="file" name="couverture" accept="image/*"><br>

    <button type="submit">Enregistrer les modifications</button>
</form>
<style>
    /* --- Style du Formulaire de Modification --- */
form[action="traitement_modifier.php"] {
    background: #111827;
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 16px;
    padding: 2.5rem;
    max-width: 500px;
    margin: 2rem auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}

h2 {
    text-align: center;
    color: #22d3ee;
    font-size: 1.8rem;
    margin-bottom: 2rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Style des labels */
form[action="traitement_modifier.php"] label {
    display: block;
    color: #94a3b8;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 8px;
    margin-top: 15px;
}

/* Style commun Inputs, Select et File */
form[action="traitement_modifier.php"] input[type="text"],
form[action="traitement_modifier.php"] input[type="number"],
form[action="traitement_modifier.php"] select,
form[action="traitement_modifier.php"] input[type="file"] {
    width: 100%;
    background: #1f2937;
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 8px;
    padding: 0.8rem;
    color: #e5e7eb;
    font-size: 1rem;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box; /* Évite que l'input dépasse du cadre */
}

/* Focus sur les champs */
form[action="traitement_modifier.php"] input:focus,
form[action="traitement_modifier.php"] select:focus {
    border-color: #22d3ee;
    box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.1);
}

/* Style spécifique au champ de fichier */
form[action="traitement_modifier.php"] input[type="file"] {
    padding: 0.5rem;
    cursor: pointer;
    font-size: 0.9rem;
}

/* Bouton Enregistrer */
form[action="traitement_modifier.php"] button {
    width: 100%;
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a;
    border: none;
    padding: 1rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    margin-top: 2rem;
    text-transform: uppercase;
    transition: transform 0.2s, opacity 0.2s;
}

form[action="traitement_modifier.php"] button:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

form[action="traitement_modifier.php"] button:active {
    transform: translateY(0);
}
</style>
<script src="dashboard.js"></script>