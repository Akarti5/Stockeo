<?php
// login.php - Version avec débogage
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require 'config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Données invalides');
    }
    
    $email = trim($input['email'] ?? '');
    $mot_de_passe = $input['mot_de_passe'] ?? '';
    
    if (empty($email) || empty($mot_de_passe)) {
        throw new Exception('Email et mot de passe sont obligatoires');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email invalide');
    }
    
    // Debug : log de la tentative de connexion
    error_log("Tentative de connexion pour: " . $email);
    
    // Rechercher l'utilisateur
    $stmt = $pdo->prepare("SELECT id, nom, email, mot_de_passe FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        error_log("Utilisateur non trouvé: " . $email);
        throw new Exception('Email ou mot de passe incorrect');
    }
    
    // Debug : utilisateur trouvé
    error_log("Utilisateur trouvé: " . $user['nom']);
    
    if (!password_verify($mot_de_passe, $user['mot_de_passe'])) {
        error_log("Mot de passe incorrect pour: " . $email);
        throw new Exception('Email ou mot de passe incorrect');
    }
    
    // Debug : mot de passe correct
    error_log("Connexion réussie pour: " . $email);
    
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['nom'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Connexion réussie',
        'user' => [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'email' => $user['email']
        ],
        'session_id' => session_id()
    ]);
    
} catch (PDOException $e) {
    error_log('Erreur PDO login: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données : ' . $e->getMessage(),
        'debug' => $e->getTraceAsString()
    ]);
    
} catch (Exception $e) {
    error_log('Erreur login: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>