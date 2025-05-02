<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autoriser toutes les origines (à restreindre en production)

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT numproduit, design, prix, quantite FROM Produit");
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter le montant à chaque produit
        $produitsAvecMontant = array_map(function ($produit) {
            $produit['montant'] = $produit['quantite'] * $produit['prix'];
            return $produit;
        }, $produits);

        echo json_encode($produitsAvecMontant);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Erreur lors de la récupération des produits', 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>