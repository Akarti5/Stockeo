<?php
$host = 'localhost';       // Adresse de votre serveur PostgreSQL (e.g., localhost)
$dbname = 'gsp_database'; // Nom de la base de données
$user = 'postgres';    // Nom d'utilisateur PostgreSQL
$password = 'votre mot de passe postgres'; // Mot de passe PostgreSQL

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    // Configuration pour afficher les erreurs PDO en mode exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>