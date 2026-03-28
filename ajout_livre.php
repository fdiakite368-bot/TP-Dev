<?php
require_once 'verif_session.php'; // Vérifie que l'utilisateur est connecté

// Seul l'admin peut ajouter un livre
if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

require_once 'connexion_db.php';
?>

<h1>Ajouter un livre</h1>
<link rel="stylesheet" href="dashboard.css">
<form action="traitement_ajout_livre.php" method="POST" enctype="multipart/form-data">

    <label>Titre :</label>
    <input type="text" name="livre_id" required>

    <label>Genre :</label>
    <input type="text" name="genre">

    <label>Nombre de pages :</label>
    <input type="number" name="nb_pages">

    <label>Nombre d'exemplaires :</label>
    <input type="number" name="nb_exemplaires" value="1">

    <label>Auteur :</label>
    <input type="text" name="prenom_auteur" placeholder="Prénom" required>
    <input type="text" name="nom_auteur" placeholder="Nom" required>
    

    <label>Couverture (jpg/png/webp) :</label>
    <input type="file" name="couverture" accept="image/*">

    <button type="submit">Ajouter</button>
</form>
<style>
    /* --- Style du Formulaire d'Ajout de Livre --- */
form[action="traitement_ajout_livre.php"] {
    background: #111827; /* --surface */
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 16px;
    padding: 2.5rem;
    max-width: 500px;
    margin: 2rem auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
}

h1 {
    text-align: center;
    color: #22d3ee; /* Cyan */
    font-size: 2rem;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
}

/* Style des étiquettes (labels) */
form[action="traitement_ajout_livre.php"] label {
    display: block;
    color: #94a3b8;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 1.2rem;
    margin-bottom: 0.5rem;
}

/* Style des champs (Text, Number, Select) */
form[action="traitement_ajout_livre.php"] input, 
form[action="traitement_ajout_livre.php"] select {
    width: 100%;
    background: #1f2937;
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 8px;
    padding: 0.8rem;
    color: #e5e7eb;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box; /* Très important pour l'alignement */
}

/* Effet au clic (Focus) */
form[action="traitement_ajout_livre.php"] input:focus, 
form[action="traitement_ajout_livre.php"] select:focus {
    border-color: #22d3ee;
    box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.1);
}

/* Style du bouton Ajouter */
form[action="traitement_ajout_livre.php"] button
</style>

<script src="dashboard.js"></script>