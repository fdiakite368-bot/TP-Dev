<?php
require_once 'verif_session.php'; 
if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

require_once 'connexion_db.php';

// Vérification de l'ID
if (!isset($_GET['id'])) {
    echo "Aucune lecture sélectionnée.";
    exit;
}

$id = intval($_GET['id']);

// Requête préparée pour la sécurité
$stmt = $conn->prepare("DELETE FROM lecture WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: lectures.php?msg=Suppression réussie");
    exit();
} else {
    echo "Erreur lors de la suppression.";
}
?>
