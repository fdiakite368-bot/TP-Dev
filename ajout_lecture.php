<?php
require_once 'verif_session.php'; // Vérifie que l'utilisateur est connecté

// L'admin et le modérateur peuvent ajouter un livre
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'modérateur') {
    die("Accès refusé.");
}

require_once 'connexion_db.php';
?>
<link rel="stylesheet" href="dashboard.css">
<h1>Ajouter une lecture</h1>

<form action="traitement_ajout_lecture.php" method="POST">

    <label>Livre :</label>
    <select name="livre_id" required>
        <?php
        $livres = mysqli_query($conn, "SELECT id, titre FROM livres");
        while ($l = mysqli_fetch_assoc($livres)) {
            echo "<option value='{$l['id']}'>{$l['titre']}</option>";
        }
        ?>
    </select>

    <label>Utilisateur :</label>
    <select name="utilisateur_id" required>
        <?php
        $users = mysqli_query($conn, "SELECT id, nom FROM utilisateurs");
        while ($u = mysqli_fetch_assoc($users)) {
            echo "<option value='{$u['id']}'>{$u['nom']}</option>";
        }
        ?>
    </select>

    <label>Statut :</label>
    <select name="statut" required>
        <option value="en cours">En cours</option>
        <option value="terminé">Terminé</option>
        <option value="abandonné">Abandonné</option>
    </select>

    <button type="submit">Ajouter</button>
</form>
<style>/* --- Style du Formulaire d'Ajout --- */

/* On réutilise une structure de container pour centrer le formulaire */
form {
    max-width: 500px;
    margin: 2rem auto;
    background: #1e293b; /* Fond légèrement plus clair que le body */
    padding: 30px;
    border-radius: 16px;
    border: 1px solid rgba(34, 211, 238, 0.2);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

h1 {
    text-align: center;
    color: #22d3ee;
    margin-bottom: 1.5rem;
}

/* Style des labels */
label {
    display: block;
    margin-bottom: 8px;
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Style des champs Select */
select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 25px;
    background-color: #0f172a; /* Fond très sombre pour le contraste */
    border: 1px solid #334155;
    border-radius: 8px;
    color: #f1f5f9;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    appearance: none; /* Enlève le style par défaut du navigateur */
}

/* Effet au focus sur le select */
select:focus {
    border-color: #22d3ee;
    box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.1);
}

/* Style du bouton de validation */
button[type="submit"] {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    border: none;
    border-radius: 8px;
    color: #0f172a;
    font-weight: bold;
    font-size: 1rem;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

button[type="submit"]:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
    box-shadow: 0 5px 15px rgba(34, 211, 238, 0.3);
}

/* Optionnel : ajout d'un lien de retour stylisé */
.back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 0.9rem;
}

.back-link:hover {
    color: #22d3ee;
}</style>