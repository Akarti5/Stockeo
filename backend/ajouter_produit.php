<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autoriser toutes les origines (à restreindre en production)
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['design']) && isset($data['prix']) && isset($data['quantite'])) {
        $design = trim($data['design']);
        $prix = filter_var($data['prix'], FILTER_VALIDATE_FLOAT);
        $quantite = filter_var($data['quantite'], FILTER_VALIDATE_INT);

        if (!empty($design) && is_numeric($prix) && is_numeric($quantite)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO Produit (design, prix, quantite) VALUES (:design, :prix, :quantite)");
                $stmt->bindParam(':design', $design);
                $stmt->bindParam(':prix', $prix);
                $stmt->bindParam(':quantite', $quantite);
                $stmt->execute();

                echo json_encode(['message' => 'Insertion réussie']);
            } catch (PDOException $e) {
                echo json_encode(['message' => 'Insertion échouée', 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Données invalides']);
        }
    } else {
        echo json_encode(['message' => 'Veuillez fournir le design, le prix et la quantité']);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>