<?php
require 'connexion_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $auteur_id = $_POST['auteur_id'];
    $pages = $_POST['pages'];

    // 1. RÉCUPÉRER L'ANCIENNE IMAGE (au cas où on n'en télécharge pas de nouvelle)
    $query = $conn->prepare("SELECT couverture FROM livres WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $livre_actuel = $result->fetch_assoc();
    $chemin_couverture = $livre_actuel['couverture']; // On garde l'ancienne par défaut

    // 2. GÉRER LE NOUVEL UPLOAD
    if (!empty($_FILES['couverture']['name'])) {
        $fichier = $_FILES['couverture'];
        $type = mime_content_type($fichier['tmp_name']);
        $taille = $fichier['size'];

        $types_valides = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($type, $types_valides)) {
            die("Format non autorisé.");
        }

        if ($taille > 2 * 1024 * 1024) {
            die("Fichier trop volumineux (max 2 Mo).");
        }

        // Créer le dossier s'il n'existe pas
        if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }

        $nom_fichier = time() . "_" . basename($fichier['name']);
        $destination = "uploads/" . $nom_fichier;

        if (move_uploaded_file($fichier['tmp_name'], $destination)) {
            $chemin_couverture = $destination; // On met à jour le chemin avec le nouveau
        }
    }

    // 3. MISE À JOUR SQL
    // Attention : bind_param doit correspondre exactement aux variables définies
    $stmt = $conn->prepare("UPDATE livres SET titre=?, auteur_id=?, nb_pages=?, couverture=? WHERE id=?");
    $stmt->bind_param("siisi", $titre, $auteur_id, $pages, $chemin_couverture, $id);

    if ($stmt->execute()) {
        header("Location: livres.php?msg=2");
        exit;
    } else {
        echo "Erreur SQL : " . $conn->error;
    }
}
?>