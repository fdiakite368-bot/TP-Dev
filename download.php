<?php
// Vérifie que l'utilisateur est connecté
require_once 'connexion_db.php';
require_once 'verif_session.php'; // bloque si pas connecté

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

// Vérification des droits
// Admin = OK
// Modérateur = OK
// Membre = OK (si tu veux limiter, on peut ajouter une condition)
if (!isset($_SESSION['role'])) {
    die("Accès refusé.");
}

// Vérifie que le fichier existe physiquement
if (!file_exists($doc['chemin'])) {
    die("Fichier introuvable sur le serveur.");
}

// Envoi du fichier au navigateur
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"" . $doc['nom'] . "\"");
header("Content-Length: " . filesize($doc['chemin']));

// Lecture du fichier
readfile($doc['chemin']);
exit;
?>
