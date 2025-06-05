<template>
    <div class="app-container">
      <!-- Main Content -->
      <main class="main-content">
        <!-- Welcome Content - Split Layout -->
        <div class="welcome-container">
          <!-- Left Side - Text Content -->
          <div class="welcome-left">
            <h1 class="welcome-title">Bienvenue sur Stockeo</h1>
            <div class="welcome-description">
              <p>
                Stockeo est une application web moderne et intuitive conçue pour révolutionner 
                la gestion de vos produits. Notre plateforme vous permet de stocker, organiser 
                et suivre vos produits avec une facilité déconcertante.
              </p>
              <p>
                Découvrez des fonctionnalités avancées comme la visualisation de listes détaillées, 
                des statistiques en temps réel, des graphiques interactifs et bien plus encore. 
                Que vous soyez un petit commerçant ou une grande entreprise, Stockeo s'adapte 
                à vos besoins spécifiques.
              </p>
              <p>
                Simplifiez votre gestion d'inventaire, optimisez vos processus et prenez des 
                décisions éclairées grâce à nos outils d'analyse performants. Avec Stockeo, 
                la gestion de stock devient un jeu d'enfant.
              </p>
            </div>
          </div>
  
          <!-- Right Side - Logo and Button -->
          <div class="welcome-right">
            <div class="logo-container">
              <div class="logo-placeholder">
                <!-- Your 500x500 logo image -->
                <img src="../assets/Stockeo.png" alt="Stockeo Logo" class="logo-image" />
              </div>
            </div>
            
            <button class="start-button" @click="startUsing">
              Commencer à utiliser
            </button>
          </div>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  import { RouterLink } from 'vue-router';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  
  const startUsing = () => {
    // Navigate to the main application
    router.push('/ajouter');
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
  
  /* App container */
  .app-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    width: 100%;
    margin: 0;
    padding: 0;
    background-color: white;
  }
  
  /* Main content */
  .main-content {
    flex: 1;
    background: white;
    color: #000000;
    padding: 2rem;
    border-radius: 0;
    box-shadow: none;
    min-height: calc(100vh - 155px);
    position: relative;
    overflow: hidden;
  }
  
  /* Welcome Container - Split Layout */
  .welcome-container {
    display: flex;
    height: 100%;
    min-height: 70vh;
    position: relative;
    z-index: 1;
    gap: 4rem;
  }
  
  /* Left Side - Text Content */
  .welcome-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-right: 2rem;
    font-family: 'Poppins', sans-serif;
  }
  
  .welcome-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: #3B82F6;
    margin-bottom: 2rem;
    line-height: 1.2;
  }
  
  .welcome-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #000000;
    font-weight: 400;
  }
  
  .welcome-description p {
    margin-bottom: 1.5rem;
    opacity: 1;
  }
  
  /* Right Side - Logo and Button */
  .welcome-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding-left: 2rem;
  }
  
  /* Logo styles */
  .logo-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 3rem;
  }
  
  .logo-placeholder {
    width: 500px;
    height: 500px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background: #f8fafc;
    border: 2px solid #3B82F6;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    animation: logoFloat 6s ease-in-out infinite;
    position: relative;
    overflow: hidden;
  }
  
  .logo-placeholder::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(59, 130, 246, 0.1), transparent);
    animation: logoShine 8s ease-in-out infinite;
  }
  
  .logo-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 50%;
    transition: transform 0.3s ease;
    z-index: 2;
    position: relative;
  }
  
  .logo-image:hover {
    transform: scale(1.05);
  }
  
  /* Start Button */
  .start-button {
    background: #3B82F6;
    color: white;
    border: none;
    padding: 1.2rem 3rem;
    font-size: 1.2rem;
    font-weight: 600;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
    font-family: 'Poppins', sans-serif;
    position: relative;
    overflow: hidden;
  }
  
  .start-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
  }
  
  .start-button:hover::before {
    left: 100%;
  }
  
  .start-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(59, 130, 246, 0.4);
    background: #1E40AF;
  }
  
  .start-button:active {
    transform: translateY(-1px);
  }
  
  /* Animation keyframes */
  @keyframes logoFloat {
    0%, 100% {
      transform: translateY(0px) rotate(0deg);
    }
    50% {
      transform: translateY(-20px) rotate(5deg);
    }
  }
  
  @keyframes logoShine {
    0% {
      transform: translateX(-100%) translateY(-100%) rotate(45deg);
    }
    100% {
      transform: translateX(100%) translateY(100%) rotate(45deg);
    }
  }
  
  /* Responsive styles */
  @media (max-width: 1024px) {
    .welcome-container {
      gap: 2rem;
    }
    
    .logo-placeholder {
      width: 400px;
      height: 400px;
    }
    
    .welcome-title {
      font-size: 3rem;
    }
  }
  
  @media (max-width: 768px) {
    .welcome-container {
      flex-direction: column;
      gap: 3rem;
      text-align: center;
    }
    
    .welcome-left {
      padding-right: 0;
    }
    
    .welcome-right {
      padding-left: 0;
    }
    
    .logo-placeholder {
      width: 300px;
      height: 300px;
    }
    
    .welcome-title {
      font-size: 2.5rem;
    }
    
    .welcome-description {
      font-size: 1rem;
    }
    
    .main-content {
      padding: 1rem;
    }
  }
  
  @media (max-width: 480px) {
    .logo-placeholder {
      width: 250px;
      height: 250px;
    }
    
    .welcome-title {
      font-size: 2rem;
    }
    
    .start-button {
      padding: 1rem 2rem;
      font-size: 1rem;
    }
  }
  </style>