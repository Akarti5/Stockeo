<template>
  <div class="app-container" :class="{ 'dark-mode': isDarkMode }">
    <!-- Header -->
    <header class="header">
      <div class="header-left">
        <img src="../src/assets/logofinal.png" alt="Logo" class="logo" />
        <h1 class="app-title"></h1>
      </div>
      
      <div class="header-right">
        <div class="header-icons">
          <!-- Settings Icon -->
          <button class="icon-btn" @click="openSettings" title="Paramètres">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="3"></circle>
              <path d="m12 1 1.27 2.76L16 2.88l1.69 3.68 3.68 1.69-.88 2.77L23 12l-2.76 1.27.88 2.77-3.68 1.69L16 21.12l-2.77-.88L12 23l-1.27-2.76L8 21.12l-1.69-3.68-3.68-1.69.88-2.77L1 12l2.76-1.27-.88-2.77 3.68-1.69L8 2.88l2.77.88L12 1z"></path>
            </svg>
          </button>
          
          <!-- Dark Mode Toggle -->
          <button class="icon-btn" @click="toggleDarkMode" :title="isDarkMode ? 'Mode clair' : 'Mode sombre'">
            <svg v-if="!isDarkMode" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="4"></circle>
              <path d="m12 2 1.02 2.03L16 3l1.03 3.02L20 7l-1.03 3.02L16 11l-3.02-1.03L12 22l-1.02-2.03L8 21l-1.03-3.02L4 17l1.03-3.02L8 13l3.02 1.03L12 2z"></path>
            </svg>
            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
          </button>
          
          <!-- Notifications -->
          <button class="icon-btn notification-btn" @click="openNotifications" title="Notifications">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
              <path d="m13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span v-if="notificationCount > 0" class="notification-badge">{{ notificationCount }}</span>
          </button>
          
          <!-- User Profile -->
          <div class="user-profile" @click="toggleUserMenu">
            <div class="user-avatar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <span class="user-name">{{ userName }}</span>
            <svg class="dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="6,9 12,15 18,9"></polyline>
            </svg>
          </div>
          
          <!-- User Dropdown Menu -->
          <div v-if="showUserMenu" class="user-dropdown">
            <div class="dropdown-item" @click="viewProfile">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              Profil
            </div>
            <div class="dropdown-item" @click="logout">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16,17 21,12 16,7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              Déconnexion
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="app-body">
      <!-- Sidebar Navigation -->
      <nav class="sidebar" :class="{ 'sidebar-collapsed': isSidebarCollapsed }">
        <button class="sidebar-toggle" @click="toggleSidebar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        
        <div class="nav-menu">
          <router-link to="/ajouter" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            <span class="nav-text">Ajouter Produit</span>
          </router-link>
          
          <router-link to="/lister-modifier" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M8 6h13"></path>
              <path d="M8 12h13"></path>
              <path d="M8 18h13"></path>
              <path d="M3 6h.01"></path>
              <path d="M3 12h.01"></path>
              <path d="M3 18h.01"></path>
            </svg>
            <span class="nav-text">Liste et Modification</span>
          </router-link>
          
          <router-link to="/bilan" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 3v18h18"></path>
              <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path>
            </svg>
            <span class="nav-text">Bilan et Graphique</span>
          </router-link>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="main-content">
        <router-view></router-view>
      </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
      <p>© {{ new Date().getFullYear() }} Stockeo - Gestion des stocks des Produits</p>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { RouterLink, RouterView } from 'vue-router';

// Reactive data
const isDarkMode = ref(false);
const isSidebarCollapsed = ref(false);
const showUserMenu = ref(false);
const notificationCount = ref(3);
const userName = ref('John Doe');

// Methods
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  localStorage.setItem('darkMode', isDarkMode.value);
};

const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

const openSettings = () => {
  console.log('Opening settings...');
  // Add your settings logic here
};

const openNotifications = () => {
  console.log('Opening notifications...');
  // Add your notifications logic here
};

const viewProfile = () => {
  console.log('Viewing profile...');
  showUserMenu.value = false;
  // Add your profile logic here
};

const logout = () => {
  console.log('Logging out...');
  showUserMenu.value = false;
  // Add your logout logic here
};

// Close user menu when clicking outside
const closeUserMenu = (event) => {
  if (!event.target.closest('.user-profile') && !event.target.closest('.user-dropdown')) {
    showUserMenu.value = false;
  }
};

// Lifecycle
onMounted(() => {
  // Load dark mode preference
  const savedDarkMode = localStorage.getItem('darkMode');
  if (savedDarkMode !== null) {
    isDarkMode.value = JSON.parse(savedDarkMode);
  }
  
  // Add click listener for closing user menu
  document.addEventListener('click', closeUserMenu);
});

onUnmounted(() => {
  document.removeEventListener('click', closeUserMenu);
});
</script>

<style scoped>
/* CSS Variables for theming */
:root {
  --primary-color: #667eea;
  --secondary-color: #764ba2;
  --accent-color: #35a0d2;
  --bg-light: #ffffff;
  --bg-gray: #f8f9fa;
  --text-dark: #2c3e50;
  --text-light: #6c757d;
  --border-color: #e9ecef;
  --sidebar-width: 260px;
  --sidebar-collapsed-width: 60px;
  --header-height: 70px;
  --footer-height: 50px;
}

.dark-mode {
  --bg-light: #1a1a1a;
  --bg-gray: #2d2d2d;
  --text-dark: #ffffff;
  --text-light: #b0b0b0;
  --border-color: #404040;
}

/* App container */
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: var(--bg-gray);
  color: var(--text-dark);
  transition: all 0.3s ease;
}

/* Header */
.header {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: white;
  height: var(--header-height);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  position: relative;
  z-index: 1000;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo {
  height: 40px;
  width: auto;
}

.app-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
}

.header-right {
  display: flex;
  align-items: center;
}

.header-icons {
  display: flex;
  align-items: center;
  gap: 1rem;
  position: relative;
}

.icon-btn {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-btn:hover {
  background: rgba(255,255,255,0.1);
  transform: translateY(-2px);
}

.icon-btn svg {
  width: 20px;
  height: 20px;
}

.notification-btn {
  position: relative;
}

.notification-badge {
  position: absolute;
  top: 0;
  right: 0;
  background: #ff4757;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.user-profile:hover {
  background: rgba(255,255,255,0.1);
}

.user-avatar {
  width: 32px;
  height: 32px;
  background: rgba(255,255,255,0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-avatar svg {
  width: 18px;
  height: 18px;
}

.user-name {
  font-weight: 500;
  font-size: 0.9rem;
}

.dropdown-arrow {
  width: 16px;
  height: 16px;
  transition: transform 0.3s ease;
}

.user-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: var(--bg-light);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  min-width: 180px;
  z-index: 1001;
  margin-top: 0.5rem;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
  color: var(--text-dark);
}

.dropdown-item:hover {
  background: var(--bg-gray);
}

.dropdown-item svg {
  width: 16px;
  height: 16px;
}

/* App body */
.app-body {
  display: flex;
  flex: 1;
}

/* Sidebar */
.sidebar {
  width: var(--sidebar-width);
  background: var(--bg-light);
  border-right: 1px solid var(--border-color);
  padding: 1rem 0;
  transition: all 0.3s ease;
  position: relative;
}

.sidebar-collapsed {
  width: var(--sidebar-collapsed-width);
}

.sidebar-toggle {
  position: absolute;
  top: 1rem;
  right: -12px;
  background: var(--bg-light);
  border: 1px solid var(--border-color);
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 10;
}

.sidebar-toggle:hover {
  background: var(--accent-color);
  color: white;
  border-color: var(--accent-color);
}

.sidebar-toggle svg {
  width: 14px;
  height: 14px;
}

.nav-menu {
  padding: 2rem 0;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.75rem 1.5rem;
  text-decoration: none;
  color: var(--text-light);
  transition: all 0.3s ease;
  margin: 0.25rem 1rem;
  border-radius: 8px;
  position: relative;
}

.nav-item:hover {
  background: var(--bg-gray);
  color: var(--text-dark);
}

.nav-item-active {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
}

.nav-item-active:hover {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
}

.nav-icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

.nav-text {
  font-weight: 500;
  transition: opacity 0.3s ease;
}

.sidebar-collapsed .nav-text {
  opacity: 0;
  pointer-events: none;
}

.sidebar-collapsed .nav-item {
  justify-content: center;
  padding: 0.75rem;
}

/* Main content */
.main-content {
  flex: 1;
  padding: 2rem;
  background: var(--bg-gray);
  min-height: calc(100vh - var(--header-height) - var(--footer-height));
  overflow-y: auto;
}

/* Footer */
.footer {
  background: var(--bg-light);
  border-top: 1px solid var(--border-color);
  color: var(--text-light);
  text-align: center;
  padding: 1rem;
  height: var(--footer-height);
  display: flex;
  align-items: center;
  justify-content: center;
}

.footer p {
  margin: 0;
  font-size: 0.875rem;
}

/* Responsive design */
@media (max-width: 768px) {
  .header {
    padding: 0 1rem;
  }
  
  .header-left .app-title {
    display: none;
  }
  
  .user-name {
    display: none;
  }
  
  .sidebar {
    width: var(--sidebar-collapsed-width);
  }
  
  .nav-text {
    display: none;
  }
  
  .main-content {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .header-icons {
    gap: 0.5rem;
  }
  
  .icon-btn {
    padding: 0.25rem;
  }
  
  .user-profile {
    padding: 0.25rem 0.5rem;
  }
}

/* Smooth transitions for all interactive elements */
* {
  transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
}

/* Custom scrollbar for main content */
.main-content::-webkit-scrollbar {
  width: 6px;
}

.main-content::-webkit-scrollbar-track {
  background: var(--bg-gray);
}

.main-content::-webkit-scrollbar-thumb {
  background: var(--border-color);
  border-radius: 3px;
}

.main-content::-webkit-scrollbar-thumb:hover {
  background: var(--text-light);
}
</style>