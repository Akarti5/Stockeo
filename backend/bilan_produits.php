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
        
        // Fetch all products with their details
        $stmt = $pdo->query("SELECT numproduit, design, prix, quantite FROM Produit");
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate totals and prepare product data for frontend
        $totalMontant = 0;
        $montants = [];
        $produitsData = [];
        
        foreach ($produits as $produit) {
            $prix = floatval($produit['prix'] ?? 0);
            $quantite = intval($produit['quantite'] ?? 0);
            $montant = $prix * $quantite;
            
            // Add montant to the total
            $totalMontant += $montant;
            $montants[] = $montant;
            
            // Add product data for the frontend chart
            $produitsData[] = [
                'numproduit' => $produit['numproduit'],
                'design' => $produit['design'],
                'prix' => $prix,
                'quantite' => $quantite,
                'montant' => $montant
            ];
        }
        
        // Find min and max
        $montantMinimal = !empty($montants) ? min($montants) : 0;
        $montantMaximal = !empty($montants) ? max($montants) : 0;
        
        // Return the results as JSON
        echo json_encode([
            'totalProduits' => (int) $totalProduits,
            'totalMontant' => (float) $totalMontant,
            'montantMinimal' => (float) $montantMinimal,
            'montantMaximal' => (float) $montantMaximal,
            'produits' => $produitsData
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'message' => 'Erreur lors du calcul du bilan', 
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>