<?php
if (session_status() ===
PHP_SESSION_NONE) {
    # code...

session_start(); }

if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SESSION['role'] !== 'modérateur') {
    die("Accès refusé : vous n'avez pas les droits nécessaires.");
}