<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autoriser toutes les origines (à restreindre en production)

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Calculer le montant total
        $stmtTotal = $pdo->query("SELECT SUM(prix * quantite) AS totalMontant FROM Produit");
        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['totalMontant'] ?? 0;

        // Trouver le montant minimal
        $stmtMin = $pdo->query("SELECT MIN(prix * quantite) AS montantMinimal FROM Produit");
        $min = $stmtMin->fetch(PDO::FETCH_ASSOC)['montantMinimal'] ?? 0;

        // Trouver le montant maximal
        $stmtMax = $pdo->query("SELECT MAX(prix * quantite) AS montantMaximal FROM Produit");
        $max = $stmtMax->fetch(PDO::FETCH_ASSOC)['montantMaximal'] ?? 0;

        echo json_encode(['totalMontant' => $total, 'montantMinimal' => $min, 'montantMaximal' => $max]);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Erreur lors du calcul du bilan', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>