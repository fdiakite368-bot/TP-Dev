<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé : vous n'avez pas les droits administrateur.");
}
?>
