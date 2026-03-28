<?php
require_once 'connexion_db.php';
require_once 'verif_session.php';

// utilisateur connecté
$user_id = $_SESSION['id'];

// Vérifier que les données sont envoyées
if (!isset($_POST['lecture_id']) || !isset($_POST['page_actuelle'])) {
    echo "Données manquantes.";
    exit;
}

$lecture_id = intval($_POST['lecture_id']);
$page_actuelle = intval($_POST['page_actuelle']);

// 1) Récupérer nb_pages du livre via la lecture
$sql = "SELECT livres.nb_pages, livres.id AS livre_id
        FROM lecture
        JOIN livres ON lecture.livre_id = livres.id
        WHERE lecture.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $lecture_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Lecture introuvable.";
    exit;
}

$nb_pages = $data['nb_pages'];

// 2) Calcul du pourcentage
$pourcentage = round(($page_actuelle / $nb_pages) * 100);

// 3) Enregistrer dans la table progression
$sql2 = "INSERT INTO progression (lecture_id, utilisateur_id, page_actuelle, pourcentage)
         VALUES (?, ?, ?, ?)";

$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "iiii", $lecture_id, $user_id, $page_actuelle, $pourcentage);
mysqli_stmt_execute($stmt2);

// 4) Retour à la fiche de la lecture
header("Location: fiche_lecture.php?id=" . $lecture_id);
exit;
?>
