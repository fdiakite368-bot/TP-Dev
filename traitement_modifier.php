<?php
require 'connexion_db.php';
require 'verif_session.php';

// Seul l'admin peut modifier un livre
if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

// Vérification des données reçues
if (!isset($_POST['id'])) {
    echo "ID manquant.";
    exit;
}

$id = intval($_POST['id']);
$titre = $_POST['titre'];
$auteur_id = intval($_POST['auteur_id']);
$nb_pages = intval($_POST['nb_pages']);
$Genre = $_POST['genre'];

$nouvelle_couverture = null;

// Gestion de la couverture
if (!empty($_FILES['couverture']['name'])) {

    $type = mime_content_type($_FILES['couverture']['tmp_name']);
    $taille = $_FILES['couverture']['size'];

    // Types autorisés
    $types_valides = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($type, $types_valides)) {
        die("Format non autorisé (jpg/png/webp uniquement).");
    }

    // Taille max 2 Mo
    if ($taille > 2 * 1024 * 1024) {
        die("Fichier trop volumineux (max 2 Mo).");
    }

    // Nom unique
    $dossier = "uploads/";
    $nom_unique = time() . "_" . basename($_FILES['couverture']['name']);
    $fichier = $dossier . $nom_unique;

    // Déplacement du fichier uploadé
    if (move_uploaded_file($_FILES['couverture']['tmp_name'], $fichier)) {
        $nouvelle_couverture = $fichier;
    }
}

// Si une nouvelle couverture a été uploadée → on met à jour avec
if ($nouvelle_couverture) {
    $sql = "UPDATE livres 
            SET titre = ?, auteur_id = ?, nb_pages = ?, couverture = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siisi", $titre, $auteur_id, $nb_pages, $nouvelle_couverture, $id);
}
// Sinon → on ne touche pas à la couverture
else {
    $sql = "UPDATE livres 
            SET titre = ?, auteur_id = ?, nb_pages = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siii", $titre, $auteur_id, $nb_pages, $id);
}

mysqli_stmt_execute($stmt);

// Redirection vers la fiche du livre
header("Location: fiche_livre.php?id=" . $id);
exit;
?>
