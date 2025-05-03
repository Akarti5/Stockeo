<template>
  <div class="app-container">
    <!-- Header -->
    <header class="header">
      <h1 class="app-title">Gestion des Produits</h1>
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
  background-color: #3f51b5;
  color: white;
  padding: 1rem 2rem;
  border-radius: 0;
  width: 100%;
}

.app-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
}

/* Navigation styles */
.navigation {
  display: flex;
  background-color: #f8f9fa;
  padding: 0.75rem 2rem;
  border-bottom: 1px solid #dddddd;
  width: 100%;
}

.nav-link {
  text-decoration: none;
  padding: 1rem 8rem;
  border-radius: 5px;
  color: #3f51b5;
  border: 1px solid #3f51b5;
  font-size: 0.875rem;
  margin-right: 0.5rem;
  transition: all 0.3s ease;
}

.nav-link-active {
  background-color: #3f51b5;
  color: white;
  font-weight: bold;
}

.nav-link:hover:not(.nav-link-active) {
  background-color: rgba(63, 81, 181, 0.1);
}

/* Main content */
.main-content {
  flex: 1;
  background-color: white;
  color: #333;
  padding: 2rem;
  border-radius: 0;
  box-shadow: none;
  min-height: calc(100vh - 155px); /* Accounting for header, nav and footer */
}

/* Footer */
.footer {
  background-color: #3f51b5;
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
}
</style>