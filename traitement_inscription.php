<?php
// 1. Toujours démarrer la session en tout premier
session_start();
require_once 'connexion_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyage des entrées
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // 2. Vérifier que les champs ne sont pas vides
    if (empty($nom) || empty($email) || empty($mot_de_passe)) {
        die("Tous les champs sont obligatoires.");
    }

    // 3. Vérifier si l'email existe déjà (Requête préparée)
    $sql = "SELECT id FROM utilisateurs WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        die("Cet email est déjà utilisé.");
    }
    mysqli_stmt_close($stmt);

    // 4. Hachage du mot de passe
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // 5. Insertion en BDD
    $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'membre')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nom, $email, $hash);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        
        // 6. Message de succès et redirection
        $_SESSION['message'] = "Vous venez de vous inscrire, veuillez vous connecter.";
        header("Location: connexion.php");
        exit(); // Toujours appeler exit() après une redirection
    } else {
        mysqli_stmt_close($stmt);
        die("Erreur lors de l'inscription : " . mysqli_error($conn));
    }
}
?>