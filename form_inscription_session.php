<?php
require_once 'connexion_db.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="dashboard.css">
<h1>Inscription à une session</h1>

<form action="inscription_session.php" method="post">
    <label>Titre session:</label><br>
    <input type="text" name="livre_id" required><br><br>

    <label>Nom :</label><br>
    <input type="text" name="utilisateur_id" required><br><br>

    <label>Date d'inscription :</label><br>
    <input type="datetime-local" name="date_inscription" required><br><br>

 <button type="submit">S'inscrire</button>

</form>
<script src="dashboard.js"></script>
</body>
</html>

<style>
    /* Style du conteneur du formulaire d'inscription */
form[action="inscription_session.php"] {
    background: #111827; /* Couleur de surface sombre */
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    max-width: 350px; /* Un peu plus étroit pour l'inscription */
    margin: 2rem auto;
    text-align: left;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Style des étiquettes (labels) */
form[action="inscription_session.php"] label {
    color: #22d3ee; /* Couleur Cyan pour bien différencier */
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Style des champs de saisie (inputs) */
form[action="inscription_session.php"] input {
    width: 100%; /* Prend toute la largeur du formulaire */
    background: #1f2937;
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 6px;
    padding: 0.6rem;
    color: #e5e7eb;
    margin-top: 0.3rem; /* Espace sous le label */
    outline: none;
    transition: all 0.2s ease;
}

/* Effet quand on clique dans un champ */
form[action="inscription_session.php"] input:focus {
    border-color: #22d3ee;
    background: #111827;
    box-shadow: 0 0 0 2px rgba(34, 211, 238, 0.2);
}

/* Style du bouton S'inscrire */
form[action="inscription_session.php"] button {
    width: 100%;
    background: #22d3ee;
    color: #0f172a;
    border: none;
    border-radius: 6px;
}
 h1 {
    text-align: center;
    color: #94a3b8;
    margin-top: 2rem;
}
 </style>
