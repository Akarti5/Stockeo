<template>
  <div class="app-container">
    <!-- Header -->
    <header class="header">
      <h1 class="app-title">
        
      </h1>
      <div class="floating-particles">
        <div class="particle" v-for="n in 15" :key="n"></div>
      </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="navigation">
      <router-link to="/ajouter" class="nav-link" active-class="nav-link-active">
        Ajouter Produit
      </router-link>
      <router-link to="/lister-modifier" class="nav-link" active-class="nav-link-active">
        Liste et Modification
      </router-link>
      <router-link to="/bilan" class="nav-link" active-class="nav-link-active">
        Bilan et Graphique
      </router-link>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <router-view></router-view>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <p>© {{ new Date().getFullYear() }} Gestion des Produits</p>
    </footer>
  </div>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>

<style scoped>
/* App container */
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  width: 100%;
  margin: 0;
  padding: 0;
}

/* Header styles */
.header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  background-size: 400% 400%;
  animation: gradientShift 8s ease infinite;
  color: white;
  padding: 1rem 2rem;
  border-radius: 0;
  width: 100%;
  position: relative;
  overflow: hidden;
}

.header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
  animation: backgroundMove 10s ease infinite;
}

.app-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-left: 0vw;
  position: relative;
  z-index: 2;
  display: inline-block;
  perspective: 1000px;
}

.letter {
  display: inline-block;
  animation: 
    letterBounce 2s ease-in-out infinite,
    letterGlow 3s ease-in-out infinite,
    letterRotate 4s ease-in-out infinite;
  transform-origin: center bottom;
  transition: all 0.3s ease;
  text-shadow: 
    0 0 10px rgba(255,255,255,0.8),
    0 0 20px rgba(255,255,255,0.6),
    0 0 30px rgba(255,255,255,0.4);
}

.letter:hover {
  animation-play-state: paused;
  transform: scale(1.3) rotateY(360deg) translateY(-10px);
  color: #ffeb3b;
  text-shadow: 
    0 0 20px rgba(255,235,59,0.8),
    0 0 40px rgba(255,235,59,0.6),
    0 0 60px rgba(255,235,59,0.4);
}

/* Particules flottantes */
.floating-particles {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.particle {
  position: absolute;
  width: 4px;
  height: 4px;
  background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, transparent 70%);
  border-radius: 50%;
  animation: particleFloat 6s ease-in-out infinite;
}

.particle:nth-child(odd) {
  animation-duration: 8s;
  animation-delay: -2s;
}

.particle:nth-child(3n) {
  animation-duration: 10s;
  animation-delay: -4s;
  background: radial-gradient(circle, rgba(255,235,59,0.6) 0%, transparent 70%);
}

.particle:nth-child(1) { left: 10%; animation-delay: 0s; }
.particle:nth-child(2) { left: 20%; animation-delay: -1s; }
.particle:nth-child(3) { left: 30%; animation-delay: -2s; }
.particle:nth-child(4) { left: 40%; animation-delay: -3s; }
.particle:nth-child(5) { left: 50%; animation-delay: -4s; }
.particle:nth-child(6) { left: 60%; animation-delay: -5s; }
.particle:nth-child(7) { left: 70%; animation-delay: -6s; }
.particle:nth-child(8) { left: 80%; animation-delay: -7s; }
.particle:nth-child(9) { left: 90%; animation-delay: -8s; }
.particle:nth-child(10) { left: 15%; animation-delay: -9s; }
.particle:nth-child(11) { left: 25%; animation-delay: -10s; }
.particle:nth-child(12) { left: 35%; animation-delay: -11s; }
.particle:nth-child(13) { left: 45%; animation-delay: -12s; }
.particle:nth-child(14) { left: 55%; animation-delay: -13s; }
.particle:nth-child(15) { left: 75%; animation-delay: -14s; }

/* Animations keyframes */
@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

@keyframes backgroundMove {
  0%, 100% {
    transform: translateX(0) translateY(0);
  }
  33% {
    transform: translateX(-30px) translateY(-30px);
  }
  66% {
    transform: translateX(30px) translateY(-30px);
  }
}

@keyframes letterBounce {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0) rotateX(0);
  }
  40% {
    transform: translateY(-15px) rotateX(10deg);
  }
  60% {
    transform: translateY(-7px) rotateX(-5deg);
  }
}

@keyframes letterGlow {
  0%, 100% {
    text-shadow: 
      0 0 10px rgba(255,255,255,0.8),
      0 0 20px rgba(255,255,255,0.6),
      0 0 30px rgba(255,255,255,0.4);
  }
  50% {
    text-shadow: 
      0 0 20px rgba(255,255,255,1),
      0 0 30px rgba(255,255,255,0.8),
      0 0 40px rgba(255,255,255,0.6),
      0 0 50px rgba(135,206,250,0.4);
  }
}

@keyframes letterRotate {
  0%, 100% {
    transform: rotateY(0deg);
  }
  25% {
    transform: rotateY(5deg);
  }
  75% {
    transform: rotateY(-5deg);
  }
}

@keyframes particleFloat {
  0% {
    transform: translateY(100px) translateX(0px) scale(0);
    opacity: 0;
  }
  10% {
    opacity: 1;
  }
  90% {
    opacity: 1;
  }
  100% {
    transform: translateY(-100px) translateX(50px) scale(1);
    opacity: 0;
  }
}

/* Navigation styles */
.navigation {
  display: flex;
  background-color: #ffffff;
  padding: 0.75rem 2rem;
  border-bottom: 1px solid #dddddd;
  width: 100%;
}

.nav-link {
  text-decoration: none;
  padding: 1rem 8rem;
  border-radius: 15px;
  color: #04006c;
  border: 1px solid #3f51b5;
  font-size: 0.875rem;
  margin-right: 0.5rem;
  transition: all 0.3s ease;
}

.nav-link-active {
  background-color: #8BB5D6;
  color: white;
  font-weight: bold;
}

.nav-link:hover:not(.nav-link-active) {
  background-color: #8BB5D6;
}

/* Main content */
.main-content {
  flex: 1;
  background-color: #FAFAFA;
  color: #333;
  padding: 2rem;
  border-radius: 0;
  box-shadow: none;
  min-height: calc(100vh - 155px);
}

/* Footer */
.footer {
  background-color: #35a0d2;
  color: white;
  text-align: center;
  padding: 0.75rem;
  width: 100%;
  border-radius: 0;
  font-size: 0.75rem;
}

/* Responsive styles */
@media (max-width: 768px) {
  .app-container {
    padding: 0;
  }
  
  .header {
    padding: 0.75rem;
  }
  
  .app-title {
    font-size: 1.25rem;
  }
  
  .letter {
    font-size: 0.9em;
  }
  
  .navigation {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem;
  }
  
  .nav-link {
    margin: 0;
    padding: 0.4rem 0.75rem;
    font-size: 0.75rem;
    flex: 1;
    text-align: center;
    min-width: 80px;
  }
  
  .main-content {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .navigation {
    flex-direction: column;
  }
  
  .nav-link {
    width: 100%;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
  }
  
  .nav-link:last-child {
    margin-bottom: 0;
  }
  
  .app-title {
    font-size: 1.1rem;
  }
  
  .letter {
    font-size: 0.8em;
  }
}

/* Réduction des animations sur les appareils avec préférence pour moins de mouvement */
@media (prefers-reduced-motion: reduce) {
  .letter,
  .particle,
  .header,
  .header::before {
    animation: none;
  }
}
</style>