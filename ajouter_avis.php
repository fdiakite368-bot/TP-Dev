<?php
session_start();
require_once "connexion_db.php";

if (!isset($_SESSION['id'])) {
    die("Accès refusé.");
}

$lecture_id = $_POST['lecture_id'];
$note = $_POST['note'];
$commentaire = $_POST['commentaire'];
$user_id = $_SESSION['id'];

$sql = "INSERT INTO avis (lecture_id, utilisateur_id, note, commentaire, visible)
        VALUES (?, ?, ?, ?, 1)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiis", $lecture_id, $user_id, $note, $commentaire);
mysqli_stmt_execute($stmt);

header("Location: fiche_lecture.php?id=" . $lecture_id);
exit;
