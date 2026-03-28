<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
<link rel="stylesheet" href="dashboard.css">
<h1>Inscription</h1>

<form action="traitement_inscription.php" method="post">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Prénom :</label><br>
    <input type="text" name="prenom" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mot_de_passe" required><br><br>

    <button type="submit">S'inscrire</button>
</form>
<script src="dashboard.js"></script>
</body>
</html>
<style>/* Style du conteneur du formulaire d'inscription */
form[action="traitement_inscription.php"] {
    background: #111827; /* --surface */
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 12px;
    padding: 2rem;
    max-width: 400px;
    margin: 2rem auto; /* Centre le formulaire sur la page */
    text-align: left;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Style des labels pour les rendre plus lisibles */
form[action="traitement_inscription.php"] label {
    color: #94a3b8; /* --muted */
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 5px;
    display: inline-block;
}

/* Style des champs de saisie (Nom, Prénom, Email, MDP) */
form[action="traitement_inscription.php"] input {
    width: 100%;
    background: #1f2937; /* --surface-light */
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 8px;
    padding: 0.75rem;
    color: #e5e7eb;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

/* Effet quand on clique dans un champ */
form[action="traitement_inscription.php"] input:focus {
    border-color: #22d3ee; /* --primary */
    box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.1);
}

/* Style du bouton S'inscrire */
form[action="traitement_inscription.php"] button {
    width: 100%;
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a;
}
h1 {
    text-align: center;
    color: var(--text);
    margin-bottom: 1.5rem;
}
</style>