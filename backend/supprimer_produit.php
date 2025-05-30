<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autoriser toutes les origines (à restreindre en production)
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Récupérer l'ID à partir des paramètres de la requête (e.g., /supprimer_produit.php?id=1)
    $numProduit = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if (is_numeric($numProduit)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM Produit WHERE numProduit = :numProduit");
            $stmt->bindParam(':numProduit', $numProduit);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Suppression réussie']);
            } else {
                echo json_encode(['message' => 'Aucun produit trouvé avec cet ID']);
            }
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Suppression échouée', 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'ID du produit non valide']);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>