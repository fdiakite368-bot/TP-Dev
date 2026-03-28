<?php
session_start();
require_once 'connexion_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier si l'utilisateur existe
    $sql = "SELECT id, nom, prenom, mot_de_passe, role FROM utilisateurs WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        // Vérification du mot de passe
        if (password_verify($mot_de_passe, $row['mot_de_passe'])) {

            // Connexion réussie → on stocke les infos dans la session
            $_SESSION['id'] = $row['id'];
            $_SESSION['nom'] = $row['nom'];
            $_SESSION['prenom'] = $row['prenom'];
            $_SESSION['role'] = $row['role'];

            header("Location: dashboard.php");
            exit();
        } else {
            die("Mot de passe incorrect.");
        }

    } else {
        die("Aucun compte trouvé avec cet email.");
    }

} else {
    die("Méthode non autorisée.");
}
