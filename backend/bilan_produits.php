<?php
// Définit l'en-tête Content-Type pour indiquer que la réponse est au format JSON.
header('Content-Type: application/json');
// Définit l'en-tête Access-Control-Allow-Origin pour autoriser les requêtes depuis n'importe quelle origine.
// ATTENTION : En production, il est fortement recommandé de restreindre cette valeur à votre domaine frontend spécifique
// (ex: 'http://localhost:8080' ou 'https://votredomaine.com') pour des raisons de sécurité.
header('Access-Control-Allow-Origin: *');
// Permet au navigateur de savoir quelles méthodes HTTP sont autorisées pour les requêtes cross-origin.
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// Permet au navigateur de savoir quels en-têtes sont autorisés dans les requêtes cross-origin,
// y compris l'en-tête 'Authorization' nécessaire pour l'authentification.
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gère les requêtes OPTIONS (preflight requests) qui sont envoyées par les navigateurs avant les requêtes réelles (GET, POST, etc.)
// pour vérifier les permissions CORS.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Répond avec un statut OK pour les requêtes OPTIONS
    exit; // Termine l'exécution du script
}

// Récupère tous les en-têtes de la requête HTTP.
$headers = getallheaders();

// Initialise userId à une chaîne vide.
$userId = '';

// Vérifie si l'en-tête 'Authorization' est présent.
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    // Utilise une expression régulière pour extraire le token (userId) après 'Bearer '.
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $userId = $matches[1];
    }
}

// Vérifie si l'userId a été correctement extrait et n'est pas vide.
if (empty($userId)) {
    // Si l'userId est manquant ou invalide, renvoie une réponse non autorisée (401).
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'En-tête d\'autorisation manquant ou invalide. Utilisateur non authentifié.'
    ]);
    exit; // Arrête l'exécution du script.
}

// Inclut le fichier de configuration de la base de données.
// Ce fichier est censé définir et initialiser l'objet $pdo pour la connexion à la base de données.
require 'config.php';

// Vérifie si la méthode de la requête est GET.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prépare la requête pour compter le total des produits pour l'utilisateur spécifique.
        // Utilisation de requêtes préparées pour prévenir les injections SQL.
        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM Produit WHERE user_id = ?");
        // Exécute la requête en liant l'userId au placeholder.
        $stmtCount->execute([$userId]);
        // Récupère le nombre total de produits.
        $totalProduits = $stmtCount->fetchColumn();

        // Prépare la requête pour récupérer tous les produits avec leurs détails
        // pour l'utilisateur spécifique.
        $stmtProduits = $pdo->prepare("SELECT numproduit, design, prix, quantite FROM Produit WHERE user_id = ?");
        // Exécute la requête en liant l'userId.
        $stmtProduits->execute([$userId]);
        // Récupère tous les résultats sous forme de tableau associatif.
        $produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

        // Initialise les variables pour les calculs.
        $totalMontant = 0;
        $montants = [];
        $produitsData = [];

        // Parcourt les produits récupérés pour calculer les montants et préparer les données pour le frontend.
        foreach ($produits as $produit) {
            // Convertit le prix et la quantité en types numériques appropriés, avec une valeur par défaut de 0 si manquant.
            $prix = floatval($produit['prix'] ?? 0);
            $quantite = intval($produit['quantite'] ?? 0);
            // Calcule le montant total pour ce produit.
            $montant = $prix * $quantite;

            // Ajoute le montant au total général.
            $totalMontant += $montant;
            // Ajoute le montant à un tableau temporaire pour trouver les min/max.
            $montants[] = $montant;

            // Ajoute les données du produit, y compris le montant calculé, au tableau pour le frontend.
            $produitsData[] = [
                'numproduit' => $produit['numproduit'],
                'design' => $produit['design'],
                'prix' => $prix,
                'quantite' => $quantite,
                'montant' => $montant
            ];
        }

        // Calcule le montant minimal et maximal.
        // Si aucun montant n'est trouvé (tableau vide), la valeur par défaut est 0.
        $montantMinimal = !empty($montants) ? min($montants) : 0;
        $montantMaximal = !empty($montants) ? max($montants) : 0;

        // Renvoie une réponse JSON indiquant le succès et toutes les données calculées.
        echo json_encode([
            'success' => true, // Indique que l'opération a réussi
            'totalProduits' => (int) $totalProduits, // Convertit en entier
            'totalMontant' => (float) $totalMontant, // Convertit en flottant
            'montantMinimal' => (float) $montantMinimal, // Convertit en flottant
            'montantMaximal' => (float) $montantMaximal, // Convertit en flottant
            'produits' => $produitsData // Tableau des produits avec leurs montants
        ]);

    } catch (PDOException $e) {
        // En cas d'erreur de base de données, renvoie une réponse d'erreur serveur (500).
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération ou du calcul du bilan. Veuillez réessayer plus tard.',
            'error_details' => $e->getMessage() // Détails de l'erreur pour le débogage (à masquer en production)
        ]);
    }
} else {
    // Si la méthode HTTP n'est pas GET, renvoie une réponse "Méthode non autorisée" (405).
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée. Seule la méthode GET est acceptée pour cette ressource.'
    ]);
}
?>