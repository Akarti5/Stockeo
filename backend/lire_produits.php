<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require 'config.php';

// Fonction pour vérifier et récupérer l'utilisateur connecté
function getCurrentUser($pdo, $headers) {
    // Vérifier si l'ID utilisateur est envoyé dans les headers ou les paramètres GET
    $userId = null;
    
    // Option 1: Récupérer depuis les headers Authorization
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        // Format attendu: "Bearer user_id" ou juste "user_id"
        $userId = str_replace('Bearer ', '', $authHeader);
    }
    
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $headers = getallheaders();
    
    // Récupérer l'ID utilisateur
    $userId = getCurrentUser($pdo, $headers);
    
    // Si pas dans les headers, vérifier dans les paramètres GET
    if (!$userId && isset($_GET['user_id'])) {
        $userId = $_GET['user_id'];
    }

    // Vérifier que l'utilisateur est fourni
    if (!$userId) {
        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur non identifié. Veuillez vous reconnecter.',
            'data' => []
        ]);
        exit;
    }

    // Valider que l'utilisateur existe
    if (!validateUser($pdo, $userId)) {
        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur invalide.',
            'data' => []
        ]);
        exit;
    }

    try {
        // Vérifier si un terme de recherche est fourni
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $search = '%' . $searchTerm . '%';
                     
            // Log pour débogage
            error_log("Terme de recherche reçu: " . $searchTerm);
            error_log("Pattern de recherche: " . $search);
            error_log("User ID: " . $userId);
                     
            // Requête avec filtre de recherche ET filtre utilisateur
            $stmt = $pdo->prepare("SELECT numproduit, design, prix, quantite, user_id
                                   FROM Produit 
                                   WHERE user_id = :user_id 
                                   AND (CAST(numproduit AS CHAR) LIKE :search
                                   OR LOWER(design) LIKE LOWER(:search2))
                                   ORDER BY numproduit DESC");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':search', $search);
            $stmt->bindParam(':search2', $search);
            $stmt->execute();
                     
            // Log du nombre de résultats
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre de produits trouvés pour user $userId: " . count($produits));
                 
        } else {
            // Requête sans filtre de recherche mais avec filtre utilisateur
            $stmt = $pdo->prepare("SELECT numproduit, design, prix, quantite, user_id 
                                   FROM Produit 
                                   WHERE user_id = :user_id 
                                   ORDER BY numproduit DESC");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Nombre total de produits pour user $userId: " . count($produits));
        }

        // Ajouter le montant à chaque produit
        $produitsAvecMontant = array_map(function ($produit) {
            $produit['montant'] = $produit['quantite'] * $produit['prix'];
            return $produit;
        }, $produits);

        echo json_encode($produitsAvecMontant);
    } catch (PDOException $e) {
        error_log("Erreur SQL: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération des produits',
            'error' => $e->getMessage(),
            'data' => []
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée',
        'data' => []
    ]);
}
?>