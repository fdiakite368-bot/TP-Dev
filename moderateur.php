<?php
require_once 'verif_session.php';
require_once 'connexion_db.php';

$role = $_SESSION['role'] ?? '';
$prenom = $_SESSION['prenom'] ?? 'Utilisateur';

$sqlLivres = "SELECT l.*, a.nom AS nom_auteur, a.prenom AS prenom_auteur
              FROM livres l
              LEFT JOIN auteurs a ON l.auteur_id = a.id
              ORDER BY l.id DESC";
$resultLivres = mysqli_query($conn, $sqlLivres);
$livres = $resultLivres ? mysqli_fetch_all($resultLivres, MYSQLI_ASSOC) : [];

$sqlSessions = "SELECT s.*, l.titre AS livre_titre
                FROM sessions_lecture s
                LEFT JOIN livres l ON s.livre_id = l.id
                ORDER BY s.date_heure DESC";
$resultSessions = $conn->query($sqlSessions);
if (!$resultSessions) {
    die("Erreur lors de la récupération des sessions : " . $conn->error);
}
$sessionsLecture = $resultSessions->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="dashboard.js"></script>
</head>
<link rel="stylesheet" href="dashboard.css">
    <script src="dashboard.js"></script>
<body>
    <header class="topbar">
        <a href="dashboard.php" class="logo">BookClub</a>
        <button id="menuToggle" class="menu-toggle" type="button" aria-label="Ouvrir le menu">Menu</button>
        <nav id="mainNav" class="nav-links">
            <a href="livres_ajoute.php">Livres</a>
            <a href="sessions_ajoute.php">Sessions</a>
            <?php if ($role === 'modérateur' || $role === 'admin') : ?>
                <a href="#moderateur-droits">Modérateur</a>
            <?php endif; ?>
            <a href="deconnexion.php" class="danger-link">Déconnexion</a>
        </nav>
    </header>

    <main class="page">
        <section class="hero">
            <h1>Bienvenue <?= htmlspecialchars($prenom) ?> !</h1>
            <p>
                Votre rôle actuel:
                <span class="badge-role"><?= htmlspecialchars($role) ?></span>
            </p>
        </section>

        <section class="roles-grid">
            <?php if ($role === 'modérateur') : ?>
                <article id="moderateur-droits" class="role-card">
                    <h2>Droits du modérateur</h2>
                    <p>Gérer les sessions et consulter les inscriptions.</p>
                    <div class="role-actions">
                        <a href="creer_session.php" class="btn">Ajouter une session</a>
                        <a href="form_inscription_moderateur.php" class="btn btn-outline">Voir les inscriptions</a>
                    </div>
                </article>
            <?php endif; ?>
        </section>