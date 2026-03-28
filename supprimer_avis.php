<?php
session_start();
require_once "connexion_db.php";

$id = $_GET['id'];

$sql = "SELECT utilisateur_id, lecture_id FROM avis WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$avis = mysqli_fetch_assoc($result);

if ($avis['utilisateur_id'] != $_SESSION['id']) {
    die("Accès refusé.");
}

$sql = "DELETE FROM avis WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: fiche_lecture.php?id=" . $avis['lecture_id']);
exit;
