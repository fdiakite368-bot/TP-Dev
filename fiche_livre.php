<?php
require_once 'connexion_db.php';
require_once 'verif_session.php';

// Vérifier que l'ID du livre est présent dans l'URL
if (!isset($_GET['id'])) {
    echo "Aucun livre sélectionné.";
    exit;
}

// On récupère l'ID du livre
$id = intval($_GET['id']);

// 1) RÉCUPÉRATION DES INFORMATIONS DU LIVRE
$sql = "SELECT l.*, a.nom AS auteur_nom, a.prenom AS auteur_prenom
        FROM livres l
        LEFT JOIN auteurs a ON l.auteur_id = a.id
        WHERE l.id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$livre = mysqli_fetch_assoc($result);

if (!$livre) {
    echo "Livre introuvable.";
    exit;
}

// 2) RÉCUPÉRATION DES DOCUMENTS LIÉS AU LIVRE (CORRIGÉ : Jointure car lié à lecture_id)
$sql_docs = "SELECT d.* FROM documents d 
             JOIN lecture l ON d.lecture_id = l.id 
             WHERE l.livre_id = ?";
$stmt_docs = mysqli_prepare($conn, $sql_docs);
mysqli_stmt_bind_param($stmt_docs, "i", $id);
mysqli_stmt_execute($stmt_docs);
$docs = mysqli_stmt_get_result($stmt_docs);

// 3) AVIS (avec auteur + date)
$sql_avis = "SELECT a.*, u.nom AS auteur_avis_nom
             FROM avis a
             JOIN lecture l ON a.lecture_id = l.id
             LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
             WHERE l.livre_id = ? AND a.visible = 1
             ORDER BY a.date_avis DESC";
$stmt_avis = mysqli_prepare($conn, $sql_avis);
mysqli_stmt_bind_param($stmt_avis, "i", $id);
mysqli_stmt_execute($stmt_avis);
$avis = mysqli_stmt_get_result($stmt_avis);

// 4) CALCUL DE LA PROGRESSION MOYENNE
$sql_prog = "SELECT AVG(pourcentage) AS progression_moyenne
             FROM progression p
             JOIN lecture l ON p.lecture_id = l.id
             WHERE l.livre_id = ?";

$stmt_prog = mysqli_prepare($conn, $sql_prog);
mysqli_stmt_bind_param($stmt_prog, "i", $id);
mysqli_stmt_execute($stmt_prog);
$result_prog = mysqli_stmt_get_result($stmt_prog);
$progression = mysqli_fetch_assoc($result_prog);

// 5) Toutes les lectures enregistrées pour ce livre
$sql_toutes = "SELECT lecture.*, u.nom AS utilisateur_nom
               FROM lecture
               INNER JOIN utilisateurs u ON lecture.utilisateur_id = u.id
               WHERE lecture.livre_id = ?
               ORDER BY lecture.id DESC";
$stmt_toutes = mysqli_prepare($conn, $sql_toutes);
mysqli_stmt_bind_param($stmt_toutes, "i", $id);
mysqli_stmt_execute($stmt_toutes);
$toutes_lectures = mysqli_stmt_get_result($stmt_toutes);

// 6) Ma lecture (ligne complète + nom utilisateur)
$sql_lecture = "SELECT lecture.*, u.nom AS utilisateur_nom
                FROM lecture
                INNER JOIN utilisateurs u ON lecture.utilisateur_id = u.id
                WHERE lecture.utilisateur_id = ? AND lecture.livre_id = ?";
$stmt_lecture = mysqli_prepare($conn, $sql_lecture);
mysqli_stmt_bind_param($stmt_lecture, "ii", $_SESSION['id'], $livre['id']);
mysqli_stmt_execute($stmt_lecture);
$lecture_user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_lecture));

$progression_user = null;
$docs_ma_lecture = null;
if ($lecture_user) {
    $sql_prog_user = "SELECT pourcentage, page_actuelle, date_maj
                      FROM progression
                      WHERE lecture_id = ? AND utilisateur_id = ?
                      ORDER BY id DESC
                      LIMIT 1";
    $stmt_prog_user = mysqli_prepare($conn, $sql_prog_user);
    mysqli_stmt_bind_param($stmt_prog_user, "ii", $lecture_user['id'], $_SESSION['id']);
    mysqli_stmt_execute($stmt_prog_user);
    $progression_user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_prog_user));

    $sql_docs_ml = "SELECT * FROM documents WHERE lecture_id = ? ORDER BY id DESC";
    $stmt_docs_ml = mysqli_prepare($conn, $sql_docs_ml);
    mysqli_stmt_bind_param($stmt_docs_ml, "i", $lecture_user['id']);
    mysqli_stmt_execute($stmt_docs_ml);
    $docs_ma_lecture = mysqli_stmt_get_result($stmt_docs_ml);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="dashboard.css">
    <meta charset="UTF-8">
    <title>Fiche du livre - <?= htmlspecialchars($livre['titre']) ?></title>
</head>
<body>

<div class="container">
    <a href="dashboard.php">← Retour sur le Dashboard</a>

    <h1><?= htmlspecialchars($livre['titre']) ?></h1>

    <?php if ($livre['couverture']) : ?>
        <img src="<?= htmlspecialchars($livre['couverture']) ?>" alt="Couverture">
    <?php endif; ?>

    <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur_prenom'] . " " . $livre['auteur_nom']) ?></p>
    <p><strong>Genre :</strong> <?= htmlspecialchars($livre['genre']) ?></p>
    <p><strong>Nombre de pages :</strong> <?= $livre['nb_pages'] ?></p>
    <p><strong>Exemplaires :</strong> <?= $livre['nb_exemplaires'] ?></p>

    <h2>Lectures enregistrées pour ce livre</h2>
    <?php if (mysqli_num_rows($toutes_lectures) > 0) : ?>
        <ul class="liste-lectures">
            <?php
            mysqli_data_seek($toutes_lectures, 0);
            while ($lec = mysqli_fetch_assoc($toutes_lectures)) :
                $est_moi = ((int) $lec['utilisateur_id'] === (int) $_SESSION['id']);
                ?>
                <li>
                    <strong><?= htmlspecialchars($lec['utilisateur_nom']) ?></strong>
                    — statut : <em><?= htmlspecialchars($lec['statut']) ?></em>
                    <a href="fiche_lecture.php?id=<?= (int) $lec['id'] ?>">Voir la fiche lecture</a>
                    <?php if ($est_moi) : ?><span class="badge-moi">(vous)</span><?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p>Aucune lecture enregistrée pour ce livre pour le moment.</p>
    <?php endif; ?>
    <h2>Avis des lecteurs</h2>

    <?php if ($lecture_user) : ?>
        <h3>Ajouter un avis</h3>
        <form action="ajouter_avis.php" method="post">
            <input type="hidden" name="lecture_id" value="<?= (int) $lecture_user['id'] ?>">

            <label>Note :</label>
            <select name="note" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <label>Commentaire :</label>
            <textarea name="commentaire" required></textarea>

            <button type="submit">Publier</button>
        </form>
    <?php endif; ?>

    <?php if (mysqli_num_rows($avis) > 0) : ?>
        <?php while ($a = mysqli_fetch_assoc($avis)) : ?>
            <div class="avis">
                <p><strong>Note :</strong> <?= (int) $a['note'] ?>/5</p>
                <p class="avis-meta">
                    Par <?= htmlspecialchars($a['auteur_avis_nom'] ?? 'Lecteur') ?>
                    <?php if (!empty($a['date_avis'])) : ?>
                        — <?= htmlspecialchars(date('d/m/Y à H:i', strtotime($a['date_avis']))) ?>
                    <?php endif; ?>
                </p>
                <p><?= nl2br(htmlspecialchars($a['commentaire'])) ?></p>

                <?php if ($_SESSION['id'] == $a['utilisateur_id']) : ?>
                    <a href="modifier_avis.php?id=<?= (int) $a['id'] ?>">Modifier</a>
                    <a href="supprimer_avis.php?id=<?= (int) $a['id'] ?>" onclick="return confirm('Supprimer cet avis ?');">Supprimer</a>
                <?php endif; ?>

                <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'modérateur') : ?>
                    <a href="masquer_avis.php?id=<?= (int) $a['id'] ?>" onclick="return confirm('Masquer cet avis ?');">Masquer</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Aucun avis pour le moment.</p>
    <?php endif; ?>

    <h2>Progression moyenne</h2>
    <p class="stats-box">
        <?php
        if (isset($progression['progression_moyenne']) && $progression['progression_moyenne'] !== null) {
            echo '<strong>Progression moyenne des lecteurs :</strong> ' . round((float) $progression['progression_moyenne'], 1) . '%';
        } else {
            echo 'Aucune progression enregistrée pour ce livre.';
        }
        ?>
    </p>

    <h2>Votre progression</h2>
    <?php if ($lecture_user) : ?>
        <p>
            <?php
            if ($progression_user && $progression_user['pourcentage'] !== null) {
                echo "Vous avez lu <strong>" . round((float) $progression_user['pourcentage'], 1) . "%</strong> du livre.";
                if (isset($progression_user['page_actuelle'])) {
                    echo " Dernière page enregistrée : <strong>" . (int) $progression_user['page_actuelle'] . "</strong>.";
                }
                if (!empty($progression_user['date_maj'])) {
                    echo " <span class=\"muted\">(mise à jour le " . htmlspecialchars(date('d/m/Y à H:i', strtotime($progression_user['date_maj']))) . ")</span>";
                }
            } else {
                echo "Vous n'avez pas encore enregistré de progression.";
            }
            ?>
        </p>

        <form action="ajouter_progression.php" method="POST">
            <input type="hidden" name="lecture_id" value="<?= (int) $lecture_user['id'] ?>">

            <label>Page actuelle :</label>
            <input type="number" name="page_actuelle" min="1" max="<?= (int) $livre['nb_pages'] ?>" required>

            <button type="submit">Mettre à jour</button>
        </form>

        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'modérateur') : ?>
            <h3>Ajouter un document PDF</h3>
            <form action="upload_pdf.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lecture_id" value="<?= (int) $lecture_user['id'] ?>">

                <label for="pdf">Choisir un PDF :</label>
                <input type="file" name="pdf" id="pdf" accept="application/pdf" required>

                <button type="submit">Uploader</button>
            </form>
        <?php endif; ?>

        <h3>Documents liés à ma lecture</h3>
        <?php if ($docs_ma_lecture && mysqli_num_rows($docs_ma_lecture) > 0) : ?>
            <?php
            mysqli_data_seek($docs_ma_lecture, 0);
            while ($doc = mysqli_fetch_assoc($docs_ma_lecture)) :
                ?>
                <p>
                    <a href="download.php?id=<?= (int) $doc['id'] ?>">
                        Télécharger <?= htmlspecialchars($doc['nom']) ?>
                    </a>
                    <?php if ($_SESSION['role'] === 'admin') : ?>
                        | <a href="supprimer_document.php?id=<?= (int) $doc['id'] ?>" class="doc-action">Supprimer</a>
                    <?php endif; ?>
                </p>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Aucun document n’a encore été ajouté pour votre lecture.</p>
        <?php endif; ?>
    <?php else : ?>
        <p>Créez une lecture pour ce livre pour suivre votre progression ici.</p>
    <?php endif; ?>

    <h2>Documents liés à toutes les lectures de ce livre</h2>
    <?php if (mysqli_num_rows($docs) > 0) : ?>
        <?php while ($doc = mysqli_fetch_assoc($docs)) : ?>
            <p>
                <a href="download.php?id=<?= (int) $doc['id'] ?>">
                    📄 <?= htmlspecialchars($doc['nom']) ?>
                </a>
                <?php if ($_SESSION['role'] === 'admin') : ?>
                    | <a href="supprimer_document.php?id=<?= (int) $doc['id'] ?>" class="doc-action">Supprimer</a>
                <?php endif; ?>
            </p>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Aucun document lié aux lectures de ce livre.</p>
    <?php endif; ?>

</div>
<script src="dashboard.js"></script>
</body>
</html>

<style>
    /* --- Mise en page globale --- */
body {
    background-color: #0f172a; /* Fond très sombre */
    color: #e5e7eb;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 900px;
    margin: 3rem auto;
    background: #111827; /* Surface légèrement plus claire */
    padding: 3rem;
    border-radius: 20px;
    border: 1px solid rgba(148, 163, 184, 0.1);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
}

/* Lien de retour */
a[href="livres.php"] {
    color: #94a3b8;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.2s;
}

a[href="livres.php"]:hover {
    color: #22d3ee;
}

/* --- En-tête du livre --- */
h1 {
    font-size: 2.8rem;
    color: #22d3ee; /* Cyan */
    margin: 1rem 0 2rem;
}

.container img {
    float: left;
    margin: 0 2.5rem 1.5rem 0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.05);
    max-width: 220px;
}

p {
    line-height: 1.7;
    font-size: 1.1rem;
    margin: 0.5rem 0;
}

strong {
    color: #22d3ee;
    font-weight: 600;
}

/* --- Sections --- */
h2 {
    clear: both; /* Pour passer en dessous de l'image flottante */
    font-size: 1.4rem;
    margin-top: 4rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    color: #f1f5f9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* --- Documents & téléchargements --- */
.container a[href*="download.php"] {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(34, 211, 238, 0.1);
    color: #22d3ee;
    text-decoration: none;
    border-radius: 6px;
    border: 1px solid #22d3ee;
    margin-bottom: 10px;
}

.container a[href*="download.php"]:hover {
    background: #22d3ee;
    color: #0f172a;
}

.doc-action {
    font-size: 0.85rem;
    color: #94a3b8;
    text-decoration: none;
}

.doc-action:hover {
    color: #ef4444;
}

.avis {
    background: rgba(31, 41, 55, 0.4);
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    border-left: 4px solid #22d3ee;
}

.avis p strong {
    color: #fbbf24; /* Couleur Ambre pour les notes */
}

.avis a {
    font-size: 0.85rem;
    color: #94a3b8;
    text-decoration: none;
    margin-right: 10px;
}

.avis a:hover {
    color: #ef4444;
}

h3 {
    font-size: 1.1rem;
    margin-top: 2rem;
    color: #94a3b8;
}

.liste-lectures {
    list-style: none;
    padding: 0;
    margin: 0;
}

.liste-lectures li {
    background: rgba(31, 41, 55, 0.5);
    padding: 1rem 1.25rem;
    border-radius: 10px;
    margin-bottom: 0.75rem;
    border-left: 3px solid #22d3ee;
}

.liste-lectures a {
    margin-left: 0.75rem;
    color: #22d3ee;
    font-size: 0.9rem;
}

.badge-moi {
    color: #fbbf24;
    font-size: 0.85rem;
    margin-left: 0.35rem;
}

.avis-meta {
    font-size: 0.85rem;
    color: #94a3b8 !important;
    margin: 0 0 0.5rem !important;
}

.muted {
    color: #94a3b8;
    font-size: 0.95rem;
}

/* --- Formulaires (avis & progression & PDF) --- */
form {
    background: rgba(31, 41, 55, 0.5);
    padding: 1.5rem;
    border-radius: 12px;
    margin: 1.5rem 0;
}

form label {
    display: block;
    margin-bottom: 0.5rem;
    color: #22d3ee;
}

select, textarea, input[type="number"], input[type="file"] {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    background: #0f172a;
    border: 1px solid #334155;
    padding: 0.8rem;
    border-radius: 8px;
    color: white;
    margin-bottom: 1rem;
}

textarea {
    height: 100px;
    resize: vertical;
}

form button {
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

form button:hover {
    transform: scale(1.02);
    filter: brightness(1.1);
}

/* --- Statistiques & Bouton Ma Lecture --- */
.stats-box {
    background: linear-gradient(135deg, rgba(34, 211, 238, 0.1), rgba(8, 145, 178, 0.1));
    padding: 1.5rem;
    border-radius: 12px;
    display: inline-block;
    border: 1px solid rgba(34, 211, 238, 0.2);
}

/* Bouton « Voir ma lecture » */
.btn-ma-lecture,
a[href^="fiche_lecture.php"] {
    display: inline-block;
    padding: 0.65rem 1.25rem;
    background: linear-gradient(135deg, #22d3ee, #0891b2) !important;
    color: #0f172a !important;
    font-weight: bold !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(34, 211, 238, 0.3);
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
    border-radius: 8px;
}

.btn-ma-lecture:hover,
a[href^="fiche_lecture.php"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(34, 211, 238, 0.4);
}

/* Nettoyage du float */
.container::after {
    content: "";
    display: table;
    clear: both;
}

/* Mobile responsive */
@media (max-width: 700px) {
    .container {
        width: 90%;
        padding: 1.5rem;
    }
    .container img {
        float: none;
        display: block;
        margin: 0 auto 2rem;
    }
    h1 { font-size: 2rem; text-align: center; }
}
</style>