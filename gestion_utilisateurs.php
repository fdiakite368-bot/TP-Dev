<?php
session_start();
require_once 'connexion_db.php';

// Sécurité : accès réservé à l'admin uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Cette page est réservée à l'administrateur.");
}

// Récupération des utilisateurs
$sql = "SELECT * FROM utilisateurs ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>
<style>
    body {
    
    display: grid; place-items: center;
    margin: 10; padding: 90px;

    }
</style>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
</head>
<body>
<link rel="stylesheet" href="dashboard.css">

<h1>Gestion des utilisateurs</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Actions</th>
    </tr>

    <?php while ($u = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td>
                <a class="btn btn-edit" href="modifier_utilisateur.php?id=<?= $u['id'] ?>">Modifier</a>
                <a class="btn btn-delete" href="supprimer_utilisateur.php?id=<?= $u['id'] ?>"
                   onclick="return confirm('Supprimer cet utilisateur ?');">
                    Supprimer
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<script src="dashboard.js"></script>
</body>
</html>
