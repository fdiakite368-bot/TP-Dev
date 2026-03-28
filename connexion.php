<?php
session_start();

// On vérifie si un message est présent en session
if (isset($_SESSION['message'])) {
    // On affiche le message (avec un peu de style si possible)
    echo '<div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">' 
         . htmlspecialchars($_SESSION['message']) . 
         '</div>';

    // IMPORTANT : On supprime le message pour qu'il ne s'affiche qu'une seule fois
    unset($_SESSION['message']);
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>

<link rel="stylesheet" href="dashboard.css">
<h1>Club de Lecture</h1>
<h1>Connexion</h1>

<form action="connexion_traitement.php" method="post">
    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mot_de_passe" required><br><br>

    <button type="submit">Se connecter</button>
</form>
<p>ou</p>
<form action="form_inscription.php" method="get">
    <button type="submit">S'inscrire</button>
</form>
<script src="dashboard.js"></script>
</body>
</html>

<style>
    /* Centrage du titre et du texte "ou" */
h1, p {
    text-align: center;
    color: var(--text);
}

p {
    margin: 10px 0;
    color: var(--muted);
    font-size: 0.9rem;
}

/* Style commun pour les deux formulaires de la page */
form[action="connexion_traitement.php"], 
form[action="form_inscription.php"] {
    background: #111827; /* --surface */
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    max-width: 350px;
    margin: 0 auto; /* Centre le formulaire */
    text-align: left;
}

/* On enlève le fond et la bordure du deuxième formulaire (Inscription) 
   pour qu'il ressemble juste à un bouton sous le "ou" */
form[action="form_inscription.php"] {
    background: transparent;
    border: none;
    padding: 0;
    margin-top: 0;
}

/* Style des labels */
label {
    color: #94a3b8;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Style des inputs */
input {
    width: 100%;
    background: #1f2937;
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 8px;
    padding: 0.7rem;
    color: #e5e7eb;
    margin-top: 5px;
    outline: none;
}

input:focus {
    border-color: #22d3ee;
}

/* Style des boutons */
button {
    width: 100%;
    padding: 0.75rem;
    border-radius: 8px;
    border: none;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}

/* Bouton Se Connecter (Principal) */
button[type="submit"] {
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a;
}

/* Bouton S'inscrire (Secondaire) */
form[action="form_inscription.php"] button {
    background: transparent;
    border: 1px solid #22d3ee;
    color: #22d3ee;
}

form[action="form_inscription.php"] button:hover {
    background: rgba(34, 211, 238, 0.1);
}
</style>