<?php
// Connexion + vérification admin
require_once 'connexion_db.php';
require_once 'verif_admin.php'; // bloque si l'utilisateur n'est pas admin

// Vérifie que l'id du document est présent
if (!isset($_GET['id'])) {
    die("Aucun document sélectionné.");
}

$id = intval($_GET['id']);

// On récupère les infos du document
$sql = "SELECT * FROM documents WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$doc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Si le document n'existe pas
if (!$doc) {
    die("Document introuvable.");
}

// Suppression du fichier physique
if (file_exists($doc['chemin'])) {
    unlink($doc['chemin']);
}

// Suppression en BDD
$sql_delete = "DELETE FROM documents WHERE id = ?";
$stmt_delete = mysqli_prepare($conn, $sql_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $id);
mysqli_stmt_execute($stmt_delete);

// Redirection vers la fiche lecture
header("Location: fiche_lecture.php?id=" . $doc['lecture_id']);
exit;
?>
