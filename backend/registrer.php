<?php
// register.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Ajustez selon vos besoins
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer les requêtes OPTIONS (préflight CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Inclure la configuration de la base de données
require 'config.php';

try {
    
    
    // Vérifier que la méthode est POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }
    
    // Récupérer les données JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Données invalides');
    }
    
    // Valider les données reçues
    $nom = trim($input['nom'] ?? '');
    $email = trim($input['email'] ?? '');
    $mot_de_passe = $input['mot_de_passe'] ?? '';
    
    // Validation
    if (empty($nom) || empty($email) || empty($mot_de_passe)) {
        throw new Exception('Tous les champs sont obligatoires');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email invalide');
    }
    
    if (strlen($mot_de_passe) < 6) {
        throw new Exception('Le mot de passe doit contenir au moins 6 caractères');
    }
    
    // Vérifier si l'email existe déjà
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    
    if ($checkStmt->fetch()) {
        throw new Exception('Cet email est déjà utilisé');
    }
    
    // Hasher le mot de passe
    $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    
    // Insérer le nouvel utilisateur
    $insertStmt = $pdo->prepare("
        INSERT INTO users (nom, email, mot_de_passe) 
        VALUES (?, ?, ?) 
        RETURNING id
    ");
    
    $insertStmt->execute([$nom, $email, $hashedPassword]);
    $newUser = $insertStmt->fetch(PDO::FETCH_ASSOC);
    
    // Réponse de succès
    echo json_encode([
        'success' => true,
        'message' => 'Compte créé avec succès',
        'user_id' => $newUser['id']
    ]);
    
} catch (PDOException $e) {
    // Erreur de base de données
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données : ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Autres erreurs
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>