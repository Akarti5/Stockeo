<?php
// logout.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

try {
    // Démarrer la session
    session_start();
    
    // Détruire toutes les variables de session
    $_SESSION = array();
    
    // Si vous voulez détruire complètement la session, effacez également le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Détruire la session
    session_destroy();
    
    echo json_encode([
        'success' => true,
        'message' => 'Déconnexion réussie'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la déconnexion'
    ]);
}
?>