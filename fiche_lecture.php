<?php
require_once 'connexion_db.php';
require_once 'verif_session.php';

// Vérifier que l'ID de la lecture est présent
if (!isset($_GET['id'])) {
    echo "Aucune lecture sélectionnée.";
    exit;
}

$id = intval($_GET['id']);

// 1) Récupération des informations de la lecture + livre + auteur + utilisateur 
$sql = "SELECT lecture.*, 
               livres.titre, livres.genre, livres.nb_pages, livres.couverture,
               auteurs.nom AS auteur_nom, auteurs.prenom AS auteur_prenom,
               utilisateurs.nom AS utilisateur_nom
        FROM lecture
        INNER JOIN livres ON lecture.livre_id = livres.id
        LEFT JOIN auteurs ON livres.auteur_id = auteurs.id
        INNER JOIN utilisateurs ON lecture.utilisateur_id = utilisateurs.id
        WHERE lecture.id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$lecture = mysqli_fetch_assoc($result);

if (!$lecture) {
    echo "Lecture introuvable.";
    exit;
}

// 2) Avis
$sql_avis = "SELECT * FROM avis WHERE lecture_id = ? AND visible = 1";
$stmt_avis = mysqli_prepare($conn, $sql_avis);
mysqli_stmt_bind_param($stmt_avis, "i", $lecture['id']);
mysqli_stmt_execute($stmt_avis);
$avis = mysqli_stmt_get_result($stmt_avis);

// 3) Progression moyenne
$sql_prog = "SELECT AVG(pourcentage) AS progression_moyenne
             FROM progression p
             JOIN lecture l ON p.lecture_id = l.id
             WHERE l.livre_id = ?";

$stmt_prog = mysqli_prepare($conn, $sql_prog);
mysqli_stmt_bind_param($stmt_prog, "i", $lecture['livre_id']);
mysqli_stmt_execute($stmt_prog);
$result_prog = mysqli_stmt_get_result($stmt_prog);
$progression = mysqli_fetch_assoc($result_prog);

// 4) Progression personnelle
$sql_prog_user = "SELECT pourcentage
                  FROM progression
                  WHERE lecture_id = ? AND utilisateur_id = ?
                  ORDER BY id DESC
                  LIMIT 1";

$stmt_prog_user = mysqli_prepare($conn, $sql_prog_user);
mysqli_stmt_bind_param($stmt_prog_user, "ii", $lecture['id'], $_SESSION['id']);
mysqli_stmt_execute($stmt_prog_user);
$result_prog_user = mysqli_stmt_get_result($stmt_prog_user);
$progression_user = mysqli_fetch_assoc($result_prog_user);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de la lecture</title>

    <style>
        .container { width: 800px; margin: auto; font-family: Arial; }
        img { max-width: 200px; margin-bottom: 20px; }
        h2 { margin-top: 40px; }
        .avis { border-bottom: 1px solid #ccc; padding: 10px 0; }
    </style>
</head>
<body>
<link rel="stylesheet" href="dashboard.css">
    
<div class="container">
<a href="lectures.php">← Retour liste lecture </a>
    <h1><?= $lecture['titre'] ?></h1>

    <?php if ($lecture['couverture']) : ?>
        <img src="<?= $lecture['couverture'] ?>" alt="Couverture du livre">
    <?php endif; ?>

    <p><strong>Auteur :</strong> <?= $lecture['auteur_prenom'] . " " . $lecture['auteur_nom'] ?></p>
    <p><strong>Genre :</strong> <?= $lecture['genre'] ?></p>
    <p><strong>Nombre de pages :</strong> <?= $lecture['nb_pages'] ?></p>

    <h2>Informations sur la lecture</h2>
    <p><strong>Utilisateur :</strong> <?= $lecture['utilisateur_nom'] ?></p>
    <p><strong>Statut :</strong> <?= $lecture['statut'] ?></p>

    <h2>Avis des lecteurs</h2>

<?php if (isset($_SESSION['id'])): ?>
    <h3>Ajouter un avis</h3>
    <form action="ajouter_avis.php" method="post">
        <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">

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
            <p><strong>Note :</strong> <?= $a['note'] ?>/5</p>
            <p><?= htmlspecialchars($a['commentaire']) ?></p>

            <?php if ($_SESSION['id'] == $a['utilisateur_id']): ?>
                <a href="modifier_avis.php?id=<?= $a['id'] ?>">Modifier</a>
                <a href="supprimer_avis.php?id=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet avis ?');">Supprimer</a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'modérateur'): ?>
                <a href="masquer_avis.php?id=<?= $a['id'] ?>" onclick="return confirm('Masquer cet avis ?');">Masquer</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p>Aucun avis pour le moment.</p>
<?php endif; ?>

      <h2>Progression moyenne</h2>
   <p>
    <?php
    // On vérifie si la variable existe ET si elle contient bien quelque chose
    if (isset($progression) && is_array($progression) && isset($progression['progression_moyenne'])) {
        echo round($progression['progression_moyenne'], 1) . "%";
    } else {
        echo "Aucune progression enregistrée.";
    }
    ?>
</p>
    <h2>Votre progression</h2>

    <p>
        <?php
        if ($progression_user && $progression_user['pourcentage'] !== null) {
            echo "Vous avez lu " . round($progression_user['pourcentage'], 1) . "% du livre.";
        } else {
            echo "Vous n'avez pas encore enregistré de progression.";
        }
        ?>
    </p>

<form action="ajouter_progression.php" method="POST">
    <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">

    <label>Page actuelle :</label>
    <input type="number" name="page_actuelle" min="1" max="<?= $lecture['nb_pages'] ?>" required>

    <button type="submit">Mettre à jour</button>
</form>

<?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'modérateur'): ?>
<h3>Ajouter un document PDF</h3>
<form action="upload_pdf.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
    
    <label for="pdf">Choisir un PDF :</label>
    <input type="file" name="pdf" id="pdf" accept="application/pdf" required>
    
    <button type="submit">Uploader</button>
</form>
<?php endif; ?>

<h3>Documents liés à cette lecture</h3>

<?php
$sql = "SELECT * FROM documents WHERE lecture_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $lecture['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<p>Aucun document n'a encore été ajouté pour cette lecture.</p>";
}

while ($doc = mysqli_fetch_assoc($result)) {
?>
    <p>
        <a href="download.php?id=<?= $doc['id'] ?>">
            Télécharger <?= htmlspecialchars($doc['nom']) ?>
        </a>

        <?php if ($_SESSION['role'] === 'admin') { ?>
            | <a href="supprimer_document.php?id=<?= $doc['id'] ?>">Supprimer</a>
        <?php } ?>
    </p>
<?php
}
?>

</div>
<script src="dashboard.js"></script>
</body>
</html>

<style>
    /* --- Mise en page globale --- */
.container {
    max-width: 900px;
    margin: 2rem auto;
    background: #111827; /* --surface */
    padding: 2.5rem;
    border-radius: 20px;
    border: 1px solid rgba(148, 163, 184, 0.1);
    color: #e5e7eb;
}

h1 {
    font-size: 2.5rem;
    color: #22d3ee;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid rgba(34, 211, 238, 0.2);
    padding-bottom: 0.5rem;
}

h2 {
    font-size: 1.4rem;
    margin-top: 3rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* --- Section Image et Infos --- */
.container img {
    float: left;
    margin-right: 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.5);
    border: 1px solid rgba(255,255,255,0.1);
}

p {
    line-height: 1.6;
    margin: 0.8rem 0;
}

strong {
    color: #22d3ee;
}

/* --- Section Avis --- */
.avis {
    background: #1f2937;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    border-left: 4px solid #22d3ee;
}

.avis p:first-child {
    font-weight: bold;
    color: #fbbf24; /* Couleur Or pour la note */
}

.avis a {
    font-size: 0.85rem;
    color: #94a3b8;
    text-decoration: none;
    margin-right: 10px;
}

.avis a:hover {
    color: #ef4444; /* Rouge pour la suppression */
}

/* --- Formulaires (Avis & Progression) --- */
form {
    background: rgba(31, 41, 55, 0.5);
    padding: 1.5rem;
    border-radius: 12px;
    margin: 1.5rem 0;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #22d3ee;
}

select, textarea, input[type="number"], input[type="file"] {
    width: 100%;
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

button {
    background: linear-gradient(135deg, #22d3ee, #0891b2);
    color: #0f172a;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.02);
    filter: brightness(1.1);
}

/* --- Documents & Liens --- */
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

/* Nettoyage du float de l'image */
.container::after {
    content: "";
    display: table;
    clear: both;
}

/* --- Mobile --- */
@media (max-width: 600px) {
    .container img {
        float: none;
        display: block;
        margin: 0 auto 1.5rem;
    }
}
</style>