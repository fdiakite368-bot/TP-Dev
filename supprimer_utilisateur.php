<?php
session_start();
require_once 'connexion_db.php';

// Sécurité : admin uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = intval($_GET['id']);

// 1️ Supprimer les avis liés aux lectures créées par cet utilisateur
$sqlAvisLectures = "
    DELETE FROM avis 
    WHERE lecture_id IN (SELECT id FROM lecture WHERE utilisateur_id = $id)
";
mysqli_query($conn, $sqlAvisLectures);

// 2️ Supprimer les lectures créées par cet utilisateur
$sqlLectures = "DELETE FROM lecture WHERE utilisateur_id = $id";
mysqli_query($conn, $sqlLectures);

// 3️ Supprimer les avis écrits par cet utilisateur
$sqlAvisUser = "DELETE FROM avis WHERE utilisateur_id = $id";
mysqli_query($conn, $sqlAvisUser);

// 4️ Supprimer l'utilisateur
$sqlUser = "DELETE FROM utilisateurs WHERE id = $id";
$result = mysqli_query($conn, $sqlUser);

if ($result) {
    header("Location: gestion_utilisateurs.php");
    exit;
} else {
    echo "Erreur lors de la suppression de l'utilisateur.";
}
?>
