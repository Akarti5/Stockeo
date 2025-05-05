<?php
error_log("Total Montant: " . $totalMontant);
error_log("Montant Minimal: " . $montantMinimal);
error_log("Montant Maximal: " . $montantMaximal);
error_log("Total produits: " . $totalProduits);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins (restrict in production)

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Calculate the total number of products
        $stmtCount = $pdo->query("SELECT COUNT(*) AS totalProduits FROM Produit");
        $totalProduits = $stmtCount->fetch(PDO::FETCH_ASSOC)['totalProduits'] ?? 0;

        // Calculate the total amount
        $stmtTotal = $pdo->query("SELECT SUM(COALESCE(prix, 0) * COALESCE(quantite, 0)) AS totalMontant FROM Produit;");
        $totalMontant = $stmtTotal->fetch(PDO::FETCH_ASSOC)['totalMontant'] ?? 0;

        // Find the minimum amount
        $stmtMin = $pdo->query("SELECT MIN(prix * quantite) AS montantMinimal FROM Produit");
        $montantMinimal = $stmtMin->fetch(PDO::FETCH_ASSOC)['montantMinimal'] ?? 0;

        // Find the maximum amount
        $stmtMax = $pdo->query("SELECT MAX(prix * quantite) AS montantMaximal FROM Produit");
        $montantMaximal = $stmtMax->fetch(PDO::FETCH_ASSOC)['montantMaximal'] ?? 0;

        echo json_encode([
            'totalProduits' => $totalProduits,
            'totalMontant' => $totalMontant,
            'montantMinimal' => $montantMinimal,
            'montantMaximal' => $montantMaximal
        ]);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Erreur lors du calcul du bilan', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>