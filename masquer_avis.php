<?php
session_start();
require_once "connexion_db.php";

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'modérateur')) {
    die("Accès refusé.");
}

$id = $_GET['id'];

$sql = "SELECT lecture_id FROM avis WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$avis = mysqli_fetch_assoc($result);

$sql = "UPDATE avis SET visible = 0 WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: fiche_lecture.php?id=" . $avis['lecture_id']);
exit;
