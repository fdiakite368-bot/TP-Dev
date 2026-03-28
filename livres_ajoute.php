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
<link rel="stylesheet" href="dashboard.css">


<body>
    <header class="topbar">
        <a href="dashboard.php" class="logo">Club de Lecture</a>
        <button id="menuToggle" class="menu-toggle" type="button" aria-label="Ouvrir le menu">Menu</button>
        <nav id="mainNav" class="nav-links">
            <a href="livres_ajoute.php">Livres</a>
            <a href="#sessions-section">Sessions</a>
            <?php if ($role === 'modérateur') : ?>
                <a href="#moderateur-droits">Modérateur</a>
            <?php endif; ?>
            <?php if ($role === 'admin') : ?>
                <a href="#admin-droits">Admin</a>
            <?php endif; ?>
            <a href="deconnexion.php" class="danger-link">Déconnexion</a>
        </nav>
    </header>

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
         <script src="dashboard.js"></script>