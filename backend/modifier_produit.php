<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autoriser toutes les origines (à restreindre en production)
header('Access-Control-Allow-Methods: PUT, PATCH');
header('Access-Control-Allow-Headers: Content-Type');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['numproduit']) && isset($data['design']) && isset($data['prix']) && isset($data['quantite'])) {
        $numproduit = filter_var($data['numproduit'], FILTER_VALIDATE_INT);
        $design = trim($data['design']);
        $prix = filter_var($data['prix'], FILTER_VALIDATE_FLOAT);
        $quantite = filter_var($data['quantite'], FILTER_VALIDATE_INT);

        if (is_numeric($numproduit) && !empty($design) && is_numeric($prix) && is_numeric($quantite)) {
            try {
                $stmt = $pdo->prepare("UPDATE Produit SET design = :design, prix = :prix, quantite = :quantite WHERE numproduit = :numproduit");
                $stmt->bindParam(':numproduit', $numproduit);
                $stmt->bindParam(':design', $design);
                $stmt->bindParam(':prix', $prix);
                $stmt->bindParam(':quantite', $quantite);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo json_encode(['message' => 'Modification réussie']);
                } else {
                    echo json_encode(['message' => 'Aucun produit trouvé avec cet ID']);
                }
            } catch (PDOException $e) {
                echo json_encode(['message' => 'Modification échouée', 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Données invalides']);
        }
    } else {
        echo json_encode(['message' => 'Veuillez fournir l\'ID du produit, le design, le prix et la quantité']);
    }
} else {
    echo json_encode(['message' => 'Méthode non autorisée']);
}
?>