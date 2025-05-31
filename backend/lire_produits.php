<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Vérifier si un terme de recherche est fourni
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $search = '%' . $searchTerm . '%';
            
            // Log pour débogage
            error_log("Terme de recherche reçu: " . $searchTerm);
            error_log("Pattern de recherche: " . $search);
            
            // Requête avec filtre de recherche (numéro ou désignation)
            // Convertir numproduit en string pour la comparaison LIKE
            $stmt = $pdo->prepare("SELECT numproduit, design, prix, quantite 
                                  FROM Produit 
                                  WHERE CAST(numproduit AS CHAR) LIKE :search 
                                  OR LOWER(design) LIKE LOWER(:search2)");
            $stmt->bindParam(':search', $search);
            $stmt->bindParam(':search2', $search);
            $stmt->execute();
            
            // Log du nombre de résultats
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de produits trouvés: " . count($produits));
            
        } else {
            // Requête sans filtre
            $stmt = $pdo->query("SELECT numproduit, design, prix, quantite FROM Produit");
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Ajouter le montant à chaque produit
        $produitsAvecMontant = array_map(function ($produit) {
            $produit['montant'] = $produit['quantite'] * $produit['prix'];
            return $produit;
        }, $produits);

        echo json_encode($produitsAvecMontant);
    } catch (PDOException $e) {
        error_log("Erreur SQL: " . $e->getMessage());
        echo json_encode(['message' => 'Erreur lors de la récupération des produits', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>