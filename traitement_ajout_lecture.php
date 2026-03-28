<?php
require_once 'verif_session.php';
// L'admin et le modérateur peuvent ajouter une lecture
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'modérateur') {
    die("Accès refusé.");
}
require_once 'connexion_db.php';

// Vérifier que les champs existent
if (!isset($_POST['livre_id'], $_POST['utilisateur_id'], $_POST['statut'])) {
    die("Erreur : données manquantes.");
}

$livre_id = $_POST['livre_id'];
$utilisateur_id = $_POST['utilisateur_id'];
$statut = $_POST['statut'];

// Requête SQL d'insertion
$sql = "INSERT INTO lecture (livre_id, utilisateur_id, statut)
        VALUES ('$livre_id', '$utilisateur_id', '$statut')";

if (mysqli_query($conn, $sql)) {
    header("Location: lectures.php"); // page que tu créeras ensuite
    exit;
} else {
    echo "Erreur SQL : " . mysqli_error($conn);
}
?>
