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
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="dashboard.css">
    <script src="dashboard.js"></script>
<body>
    <header class="topbar">
        <a href="dashboard.php" class="logo">Club de Lecture</a>
        <button id="menuToggle" class="menu-toggle" type="button" aria-label="Ouvrir le menu">Menu</button>
        <nav id="mainNav" class="nav-links">
            <a href="livres_ajoute.php">Livres</a>
            <a href="ajout_lecture.php">Lecture</a>
            <a href="sessions_ajoute.php">Sessions</a>
            <?php if ($role === 'modérateur') : ?>
                <a href="moderateur.php">Modérateur</a>
            <?php endif; ?>
            <?php if ($role === 'admin') : ?>
                <a href="admin.php">Admin</a>
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
            <?php if ($role === 'modérateur' || $role === 'admin') : ?>
                <article id="moderateur-droits" class="role-card">
                    <h2>Droits du modérateur</h2>
                    <p>Gérer les sessions et consulter les inscriptions.</p>
                    <div class="role-actions">
                        <a href="creer_session.php" class="btn">Ajouter une session</a>
                        <a href="form_inscription_moderateur.php" class="btn btn-outline">Voir les inscriptions</a>
                    </div>
                </article>
            <?php endif; ?>

            <?php if ($role === 'admin') : ?>
                <article id="admin-droits" class="role-card">
                    <h2>Droits de l'admin</h2>
                    <p>Administration complète des livres, utilisateurs et sessions.</p>
                    <div class="role-actions">
                        <a href="livres.php" class="btn">Gérer les livres</a>
                        <a href="gestion_utilisateurs.php" class="btn btn-outline">Gérer les utilisateurs</a>
                    </div>
                </article>
            <?php endif; ?>
        </section>

        <section id="livres-section" class="content-section">
            <div class="section-head">
                <h2>Livres récemment ajoutés</h2>
            </div>
            <div class="cards-grid">
                <?php if (!empty($livres)) : ?>
                    <?php foreach ($livres as $livre) : ?>
                        <?php $cheminCouverture = $livre['couverture'] ?? ''; ?>
                        <article class="card">
                            <div class="image-wrap">
                                <?php if (!empty($cheminCouverture) && file_exists($cheminCouverture)) : ?>
                                    <img src="<?= htmlspecialchars($cheminCouverture) ?>" alt="Couverture de livre">
                                <?php else : ?>
                                    <div class="image-placeholder">Pas d'image</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h3><?= htmlspecialchars($livre['titre'] ?? 'Titre inconnu') ?></h3>
                                <p>
                                    <?= htmlspecialchars(trim(($livre['prenom_auteur'] ?? '') . ' ' . ($livre['nom_auteur'] ?? ''))) ?>
                                </p>
                                <span class="tag"><?= htmlspecialchars($livre['genre'] ?? 'Genre inconnu') ?></span>
                            </div>
                            <div class="card-actions">
                                <a href="fiche_livre.php?id=<?= (int) ($livre['id'] ?? 0) ?>" class="btn">Voir le livre</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="empty">Aucun livre ajouté pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>

        <section id="sessions-section" class="content-section">
            <div class="section-head">
                <h2>Sessions de lecture</h2>
            </div>
            <div class="cards-grid sessions-grid">
                <?php if (empty($sessionsLecture)) : ?>
                    <p class="empty">Aucune session prévue pour le moment.</p>
                <?php else : ?>
                    <?php foreach ($sessionsLecture as $session) : ?>
                        <article class="card session-card">
                            <div class="card-body">
                                <h3><?= htmlspecialchars($session['livre_id'] ?? 'Livre non spécifié') ?></h3>
                                <p><strong>Date:</strong> <?= date('d/m/Y à H:i', strtotime($session['date_heure'])) ?></p>
                                <p><strong>Lieu/Lien:</strong> <?= htmlspecialchars($session['lieu_ou_lien'] ?? 'Non précisé') ?></p>
                                <p><?= htmlspecialchars($session['description'] ?? '') ?></p>
                            </div>
                            <div class="card-actions">
                                <form action="form_inscription_session.php" method="get">
                                    <input type="hidden" name="session_id" value="<?= (int) $session['id'] ?>">
                                    <button type="submit" class="btn">S'inscrire</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="dashboard.js"></script>
</body>
</html>
<script src="dashboard.js"></script>
