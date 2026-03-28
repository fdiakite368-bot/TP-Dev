<?php
require_once 'verif_session.php';
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'modérateur') {
    die("Accès refusé.");
}

// Connexion à la base de données
require 'connexion_db.php';


// Récupération de l'id de la lecture envoyé par le formulaire
$lecture_id = intval($_POST['lecture_id']);

// Vérifie si un fichier a été envoyé
if (!isset($_FILES['pdf'])) {
    die("Aucun fichier reçu.");
}

$fichier = $_FILES['pdf'];

// Vérification du type MIME pour s'assurer que c'est bien un PDF
if ($fichier['type'] !== 'application/pdf') {
    die("Le fichier doit être un PDF.");
}

// Vérification de la taille (max 5 Mo)
if ($fichier['size'] > 5 * 1024 * 1024) {
    die("Le fichier est trop volumineux (max 5 Mo).");
}

// Création d'un nom unique pour éviter les conflits
$nouveau_nom = time() . "_" . $fichier['name'];

// Chemin où sera stocké le fichier
$chemin = "uploads/" . $nouveau_nom;

// Déplacement du fichier depuis le dossier temporaire vers /uploads
move_uploaded_file($fichier['tmp_name'], $chemin);

// Préparation de la requête SQL pour enregistrer les métadonnées
$sql = "INSERT INTO documents (lecture_id, nom, chemin, taille, date_upload)
        VALUES (?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($conn, $sql);

// Liaison des paramètres : lecture_id, nom original, chemin, taille
mysqli_stmt_bind_param($stmt, "issi", 
    $lecture_id, 
    $fichier['name'], 
    $chemin, 
    $fichier['size']
);

// Exécution de la requête
mysqli_stmt_execute($stmt);

// Redirection vers la fiche de la lecture après upload
header("Location: fiche_lecture.php?id=" . $lecture_id);
exit;
?>