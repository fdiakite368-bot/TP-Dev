<?php
session_start();
require_once "connexion_db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM avis WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$avis = mysqli_fetch_assoc($result);

if ($avis['utilisateur_id'] != $_SESSION['id']) {
    die("Accès refusé.");
}
?>

<h2>Modifier votre avis</h2>

<form action="traiter_modif_avis.php" method="post">
    <input type="hidden" name="id" value="<?= $avis['id'] ?>">

    <label>Note :</label>
    <select name="note">
        <option value="1" <?= $avis['note']==1?'selected':'' ?>>1</option>
        <option value="2" <?= $avis['note']==2?'selected':'' ?>>2</option>
        <option value="3" <?= $avis['note']==3?'selected':'' ?>>3</option>
        <option value="4" <?= $avis['note']==4?'selected':'' ?>>4</option>
        <option value="5" <?= $avis['note']==5?'selected':'' ?>>5</option>
    </select>

    <label>Commentaire :</label>
    <textarea name="commentaire"><?= htmlspecialchars($avis['commentaire']) ?></textarea>

    <button type="submit">Modifier</button>
</form>
