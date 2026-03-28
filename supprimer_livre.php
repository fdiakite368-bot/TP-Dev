<?php
require_once 'verif_session.php'; // Vérifie que l'utilisateur est connecté

// Seul l'admin peut supprimer un livre
if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

require_once 'connexion_db.php';

// Vérification de l'ID
if (!isset($_GET['id'])) {
    die("Aucun livre sélectionné.");
}

$id = intval($_GET['id']);

// Vérifier si le livre est utilisé dans une lecture
$sql_check = "SELECT id FROM lecture WHERE livre_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    die("Impossible de supprimer ce livre : il est utilisé dans une ou plusieurs lectures.");
}

// Suppression du livre
$stmt = mysqli_prepare($conn, "DELETE FROM livres WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: livres.php?msg=Suppression réussie");
    exit();
} else {
    echo "Erreur lors de la suppression.";
}
