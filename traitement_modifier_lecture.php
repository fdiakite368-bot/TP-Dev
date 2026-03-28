<?php
require_once 'verif_session.php';
// L'admin et le modérateur peuvent ajouter un livre
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'modérateur') {
    die("Accès refusé.");
}
require_once 'connexion_db.php';

// Vérification des champs
if (!isset($_POST['id'], $_POST['livre_id'], $_POST['utilisateur_id'], $_POST['statut'])) {
    die("Erreur : données manquantes.");
}

$id = intval($_POST['id']);
$livre_id = $_POST['livre_id'];
$utilisateur_id = $_POST['utilisateur_id'];
$statut = $_POST['statut'];

// Requête SQL UPDATE
$sql = "UPDATE lecture 
        SET livre_id = ?, utilisateur_id = ?, statut = ?
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iisi", $livre_id, $utilisateur_id, $statut, $id);
mysqli_stmt_execute($stmt);

// Redirection
header("Location: lectures.php");
exit;
?>
