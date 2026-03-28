<?php
session_start();
require_once 'connexion_db.php';

// Sécurité : accès réservé à l'admin uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Cette page est réservée à l'administrateur.");
}

// Vérification de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = intval($_GET['id']);

// Récupération des infos de l'utilisateur
$sql = "SELECT * FROM utilisateurs WHERE id = $id";
$result = mysqli_query($conn, $sql);
$utilisateur = mysqli_fetch_assoc($result);

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $update = "UPDATE utilisateurs 
               SET nom = '$nom', email = '$email', role = '$role'
               WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        header("Location: gestion_utilisateurs.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur</title>
</head>
<body>

<h1>Modifier l'utilisateur</h1>

<form method="POST">
    <label>Nom :</label><br>
    <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required><br><br>

    <label>Rôle :</label><br>
    <select name="role" required>
        <option value="membre" <?= $utilisateur['role'] === 'membre' ? 'selected' : '' ?>>Membre</option>
        <option value="moderateur" <?= $utilisateur['role'] === 'moderateur' ? 'selected' : '' ?>>Modérateur</option>
        <option value="admin" <?= $utilisateur['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br><br>

    <button type="submit">Enregistrer</button>
</form>

</body>
</html>
<style>
    /* --- Style du Formulaire Modifier Utilisateur --- */
body {
    background-color: #0f172a; /* Fond sombre pour toute la page */
    color: #e5e7eb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 50px;
}

h1 {
    color: #22d3ee;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 2rem;
}

form {
    background: #111827;
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 16px;
    padding: 2.5rem;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
}

/* Style des labels */
label {
    display: block;
    color: #94a3b8;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 8px;
}

/* Champs de texte, Email et Select */
input[type="text"],
input[type="email"],
select {
    width: 100%;
    background: #1f2937;
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 8px;
    padding: 0.8rem;
    color: #f1f5f9;
    font-size: 1rem;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box; /* Évite que les champs dépassent */
}

/* Focus sur les champs */
input:focus, select:focus {
    border-color: #22d3ee;
    box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.1);
}

/* Personnalisation du Select (Rôle) */
select {
    cursor: pointer;
    appearance: none; /* Enlever le style par défaut du navigateur */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.2rem;
}

/* Bouton Enregistrer */
button[type="submit"] {
    width: 100%;
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a;
    border: none;
    padding: 1rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    text-transform: uppercase;
    margin-top: 1rem;
    transition: transform 0.2s, filter 0.2s;
}

button[type="submit"]:hover {
    filter: brightness(1.1);
    transform: translateY(-2px);
}

button[type="submit"]:active {
    transform: translateY(0);
}
</style>
