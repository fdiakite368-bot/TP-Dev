<?php
require_once 'connexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Récupération des données (Ligne 10 environ)
    // Assure-toi que dans ton HTML tu as bien <input name="date_heure">
    $livres_id    = $_POST['Titre'] ?? '';
    $genre       = $_POST['Genre'] ?? '';
    $date_heure  = $_POST['Date_heure'] ?? ''; 
    $lieu_ou_lien = $_POST['lieu_ou_lien'] ?? '';
    $description = $_POST['Description'] ?? '';

    // 2. Préparation (On utilise les ? pour la sécurité)
    $sql = "INSERT INTO sessions_lecture ( livre_id , genre, date_heure, lieu_ou_lien, description) 
            VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        
        // 3. Liaison des paramètres (Ligne 19)
        // "sssss" veut dire que l'on envoie 5 chaînes de caractères
        $stmt->bind_param("sssss", $livres_id , $genre, $date_heure, $lieu_ou_lien, $description);
        
        // 4. Exécution
        if ($stmt->execute()) {
            header("Location: dashboard.php?success=1");
            exit();
        } else {
            echo "Erreur lors de l'exécution : " . $stmt->error;
        }
    } else {
        echo "Erreur de préparation SQL : " . $conn->error;
    }
}
?>