<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'club_lecture';
$charset = 'utf8mb4';

// Connexion à la base de données
$conn = mysqli_connect($host, $username, $password, $dbname);

// Vérification de la connexion
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Encodage UTF-8 
mysqli_set_charset($conn, $charset);
?>
