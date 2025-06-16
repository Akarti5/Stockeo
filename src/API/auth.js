
  // API/auth.js

// Fonction pour l'inscription
export const registerUser = async (userData) => {
    try {
      const response = await fetch('http://localhost/backend/registrer.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData)
      });
  
      const result = await response.json();
      return { 
        success: response.ok, 
        data: result 
      };
    } catch (error) {
      return { 
        success: false, 
        error: error.message 
      };
    }
  }
  
  // Fonction pour la connexion
  export const loginUser = async (credentials) => {
    try {
      const response = await fetch('http://localhost/backend/login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(credentials)
      });
  
      const result = await response.json();
      return { 
        success: response.ok, 
        data: result 
      };
    } catch (error) {
      return { 
        success: false, 
        error: error.message 
      };
    }
  }
  
  // Fonction pour la déconnexion
  export const logoutUser = async () => {
    try {
      const response = await fetch('http://localhost/votre-projet/logout.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        }
      });
  
      const result = await response.json();
      
      // Nettoyer le localStorage
      localStorage.removeItem('user');
      
      return { 
        success: response.ok, 
        data: result 
      };
    } catch (error) {
      return { 
        success: false, 
        error: error.message 
      };
    }
  }