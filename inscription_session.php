<?php

require_once 'connexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Récupération des données (Ligne 10 environ)
    // Assure-toi que dans ton HTML tu as bien <input name="date_heure">
    $utilisateurs_id    = $_POST['utilisateur_id'] ?? '';
    $livres_id     = $_POST['livre_id'] ?? '';
    $date_inscription  = $_POST['date_inscription'] ?? ''; 


    // 2. Préparation (On utilise les ? pour la sécurité)
    $sql = "INSERT INTO inscriptions_session (utilisateur_id, livre_id, date_inscription) 
            VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        
        // 3. Liaison des paramètres (Ligne 19)
        // "sssss" veut dire que l'on envoie 5 chaînes de caractères
        $stmt->bind_param("sss", $utilisateurs_id , $livres_id, $date_inscription);
        
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