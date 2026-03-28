<?php
require_once 'verif_session.php';

if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

require_once 'connexion_db.php';

// 1. RÉCUPÉRATION DES DONNÉES (Note : on récupère nom/prenom au lieu de auteur_id)
$titre = $_POST['titre'];
$genre = $_POST['genre'];
$nb_pages = intval($_POST['nb_pages']);
$nb_exemplaires = intval($_POST['nb_exemplaires']);

// Nouveaux champs venant de ton formulaire
$prenom_auteur = $_POST['prenom_auteur']; 
$nom_auteur = $_POST['nom_auteur'];

$chemin_couverture = null;

// --- Bloc Upload (Inchangé) ---
if (!empty($_FILES['couverture']['name'])) {
    $fichier = $_FILES['couverture'];
    $type = mime_content_type($fichier['tmp_name']);
    $taille = $fichier['size'];
    $types_valides = ['image/jpeg', 'image/png', 'image/webp'];
    
    if (!in_array($type, $types_valides)) {
        die("Format non autorisé.");
    }
    if ($taille > 2 * 1024 * 1024) {
        die("Fichier trop volumineux.");
    }

    $nom_fichier = time() . "_" . basename($fichier['name']);
    $chemin_couverture = "uploads/" . $nom_fichier;
    move_uploaded_file($fichier['tmp_name'], $chemin_couverture);
}

// --- ÉTAPE A : CRÉATION DE L'AUTEUR ---
$sql_auteur = "INSERT INTO auteurs (prenom, nom) VALUES (?, ?)";
$stmt_auteur = mysqli_prepare($conn, $sql_auteur);
mysqli_stmt_bind_param($stmt_auteur, "ss", $prenom_auteur, $nom_auteur);
mysqli_stmt_execute($stmt_auteur);

// On récupère l'ID que la base de données vient de créer
$nouvel_auteur_id = mysqli_insert_id($conn);


// --- ÉTAPE B : INSERTION DU LIVRE ---
// On utilise $nouvel_auteur_id ici
$sql_livre = "INSERT INTO livres (titre, genre, nb_pages, nb_exemplaires, auteur_id, couverture)
              VALUES (?, ?, ?, ?, ?, ?)";

$stmt_livre = mysqli_prepare($conn, $sql_livre);
mysqli_stmt_bind_param($stmt_livre, "ssiiis", $titre, $genre, $nb_pages, $nb_exemplaires, $nouvel_auteur_id, $chemin_couverture);

if (mysqli_stmt_execute($stmt_livre)) {
    header("Location: livres.php?msg=ajout_ok");
    exit;
} else {
    echo "Erreur lors de l'ajout du livre : " . mysqli_error($conn);
}
?>