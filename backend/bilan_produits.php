<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins (restrict in production)

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Calculate the total number of products using fetchColumn()
        $stmtCount = $pdo->query("SELECT COUNT(*) FROM Produit");
        $totalProduits = $stmtCount->fetchColumn();
        
        // Fallback if above method fails
        if ($totalProduits === false) {
            $stmt = $pdo->query("SELECT numproduit FROM Produit");
            $allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalProduits = count($allProducts);
        }
        
        // Fetch all products first
        $stmt = $pdo->query("SELECT numproduit, design, prix, quantite FROM Produit");
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate totals using PHP instead of SQL
        $totalMontant = 0;
        $montants = [];
        
        foreach ($produits as $produit) {
            $prix = floatval($produit['prix'] ?? 0);
            $quantite = intval($produit['quantite'] ?? 0);
            $montant = $prix * $quantite;
            $totalMontant += $montant;
            $montants[] = $montant;
        }
        
        // Find min and max
        $montantMinimal = !empty($montants) ? min($montants) : 0;
        $montantMaximal = !empty($montants) ? max($montants) : 0;
        
        // Return the results as JSON
        echo json_encode([
            'totalProduits' => (int) $totalProduits,
            'totalMontant' => (float) $totalMontant,
            'montantMinimal' => (float) $montantMinimal,
            'montantMaximal' => (float) $montantMaximal
        ]);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Erreur lors du calcul du bilan', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>