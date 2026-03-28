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
        <a href="dashboard.php" class="logo">Club de Lecture<</a>
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