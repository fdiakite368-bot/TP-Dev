<?php 
 require_once 'connexion_db.php'; ?>
<link rel="stylesheet" href="dashboard.css">

<a href="dashboard.php">← Retour sur le Dashboard</a>
<h1>Ajouter une session de lecture</h1>
 
<form action="form_crerr_session.php" method="POST" enctype="multipart/form-data">

    <label>Titre :</label>
    <input type="text" name="Titre" required>

    <label>Genre :</label>
    <input type="text" name="Genre">

    <label>Date_heure :</label>
    <input type="datetime-local" name="Date_heure" required>

    <label>lieu ou lien  :</label>
    <input type="text" name="lieu_ou_lien" required>

    <label>description :</label>
   <input type="text" name="Description" required>

    <button type="submit">Ajouter</button>

</form>
<script src="dashboard.js"></script>
<a href="dashboard.php">← Retour sur le Dashboard</a>
<style>
    /* Style du conteneur du formulaire */
form {
    
    background: #111827; /* --surface */
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    max-width: 400px; /* Formulaire compact */
    margin: 2rem auto; /* Centré sur la page */
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    text-align: left;
}

/* Style des étiquettes (labels) */
form label {
    color: #94a3b8; /* --muted */
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: -0.4rem;
    text-transform: capitalize;
}

/* Style des champs de saisie (inputs) */
form input {
    background: #1f2937; /* --surface-light */
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 8px;
    padding: 0.6rem;
    color: #e5e7eb;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.2s;
}

form input:focus {
    border-color: #22d3ee; /* --primary */
}

/* Style du bouton Ajouter */
form button {
    margin-top: 1rem;
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a;
    border: none;
    padding: 0.7rem;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.2s;
}

form button:hover {
    opacity: 0.9;
}

/* Ajustement pour le sélecteur de date */
form input[type="datetime-local"] {
    color-scheme: dark; /* Force le calendrier en mode sombre */
}
h1 {
    text-align: center;
    color: #e5e7eb;
    margin-top: 2rem;
}
</style>