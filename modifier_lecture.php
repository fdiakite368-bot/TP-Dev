<?php
require_once 'verif_session.php';

// L'admin et le modérateur peuvent ajouter une lecture
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'modérateur') {
    die("Accès refusé.");
}

require_once 'connexion_db.php';

// Vérification de l'ID
if (!isset($_GET['id'])) {
    echo "Aucune lecture sélectionnée.";
    exit;
}

$id = intval($_GET['id']);

// Récupération de la lecture
$sql = "SELECT * FROM lecture WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$lecture = mysqli_fetch_assoc($result);

if (!$lecture) {
    echo "Lecture introuvable.";
    exit;
}
?>

<h2>Modifier la lecture</h2>

<form action="traitement_modifier_lecture.php" method="POST">

    <!-- ID de la lecture -->
    <input type="hidden" name="id" value="<?= $lecture['id'] ?>">

    <label>Livre :</label>
    <select name="livre_id">
        <?php
        $livres = mysqli_query($conn, "SELECT * FROM livres");
        while ($l = mysqli_fetch_assoc($livres)) {
            $selected = ($l['id'] == $lecture['livre_id']) ? "selected" : "";
            echo "<option value='{$l['id']}' $selected>{$l['titre']}</option>";
        }
        ?>
    </select><br>

    <label>Utilisateur :</label>
    <select name="utilisateur_id">
        <?php
        $users = mysqli_query($conn, "SELECT * FROM utilisateurs");
        while ($u = mysqli_fetch_assoc($users)) {
            $selected = ($u['id'] == $lecture['utilisateur_id']) ? "selected" : "";
            echo "<option value='{$u['id']}' $selected>{$u['nom']}</option>";
        }
        ?>
    </select><br>

    <label>Statut :</label>
    <select name="statut">
        <option value="en cours" <?= ($lecture['statut'] == 'en cours') ? "selected" : "" ?>>En cours</option>
        <option value="terminé" <?= ($lecture['statut'] == 'terminé') ? "selected" : "" ?>>Terminé</option>
        <option value="abandonné" <?= ($lecture['statut'] == 'abandonné') ? "selected" : "" ?>>Abandonné</option>
    </select><br>

    <button type="submit">Enregistrer les modifications</button>
</form>