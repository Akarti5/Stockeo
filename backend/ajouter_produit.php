<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autoriser toutes les origines (à restreindre en production)
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require 'config.php';

// Fonction pour vérifier et récupérer l'utilisateur connecté
function getCurrentUser($pdo, $headers) {
    // Vérifier si l'ID utilisateur est envoyé dans les headers ou les données
    $userId = null;
    
    // Option 1: Récupérer depuis les headers Authorization
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        // Format attendu: "Bearer user_id" ou juste "user_id"
        $userId = str_replace('Bearer ', '', $authHeader);
    }
    
    // Option 2: Si pas dans les headers, on pourra le récupérer depuis les données POST
    return $userId;
}

// Fonction pour valider que l'utilisateur existe
function validateUser($pdo, $userId) {
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    } catch (PDOException $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $headers = getallheaders();

    // Récupérer l'ID utilisateur
    $userId = getCurrentUser($pdo, $headers);
    
    // Si pas dans les headers, vérifier dans les données POST
    if (!$userId && isset($data['user_id'])) {
        $userId = $data['user_id'];
    }

    // Vérifier que l'utilisateur est fourni
    if (!$userId) {
        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur non identifié. Veuillez vous reconnecter.'
        ]);
        exit;
    }

    // Valider que l'utilisateur existe
    if (!validateUser($pdo, $userId)) {
        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur invalide.'
        ]);
        exit;
    }

    // Vérifier les données du produit
    if (isset($data['design']) && isset($data['prix']) && isset($data['quantite'])) {
        $design = trim($data['design']);
        $prix = filter_var($data['prix'], FILTER_VALIDATE_FLOAT);
        $quantite = filter_var($data['quantite'], FILTER_VALIDATE_INT);

        if (!empty($design) && is_numeric($prix) && is_numeric($quantite)) {
            try {
                // Ajouter user_id dans la requête INSERT
                $stmt = $pdo->prepare("INSERT INTO Produit (design, prix, quantite, user_id) VALUES (:design, :prix, :quantite, :user_id)");
                $stmt->bindParam(':design', $design);
                $stmt->bindParam(':prix', $prix);
                $stmt->bindParam(':quantite', $quantite);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                echo json_encode([
                    'success' => true,
                    'message' => 'Produit ajouté avec succès',
                    'product_id' => $pdo->lastInsertId()
                ]);
            } catch (PDOException $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout du produit',
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Données invalides. Vérifiez le design, le prix et la quantité.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Veuillez fournir le design, le prix et la quantité'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>