<template>
  <div class="app-container" :class="{ 'dark-mode': isDarkMode }">
    <!-- Header -->
    <header class="header">
      <div class="header-left">
        <img src="../src/assets/logofinal.png" alt="Logo" class="logo" />
        <!-- <h1 class="app-title">Stockeo</h1> -->
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
      <!-- Sidebar Navigation - Style similaire à l'image -->
      <nav class="sidebar" :class="{ 'sidebar-collapsed': isSidebarCollapsed }">
        <!-- Logo et titre dans la sidebar -->
        <div class="sidebar-header">
          <div class="sidebar-logo">
            <div class="logo-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 7h-9"></path>
                <path d="M14 17H5"></path>
                <circle cx="17" cy="17" r="3"></circle>
                <circle cx="7" cy="7" r="3"></circle>
              </svg>
            </div>
            <span class="sidebar-title">Menu</span>
          </div>
        </div>
        
        <!-- Menu de navigation -->
        <div class="nav-menu">
          <router-link to="/dashboard" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7"></rect>
              <rect x="14" y="3" width="7" height="7"></rect>
              <rect x="14" y="14" width="7" height="7"></rect>
              <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span class="nav-text">Dashboard</span>
          </router-link>
          
          <router-link to="/ajouter" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M8 6h13"></path>
              <path d="M8 12h13"></path>
              <path d="M8 18h13"></path>
              <path d="M3 6h.01"></path>
              <path d="M3 12h.01"></path>
              <path d="M3 18h.01"></path>
            </svg>
            <span class="nav-text">Ajouter Produit</span>
          </router-link>

          <router-link to="/lister-modifier" class="nav-item nav-item-active" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
              <polyline points="3.27,6.96 12,12.01 20.73,6.96"></polyline>
              <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span class="nav-text">Liste et modification</span>
          </router-link>
          
          <router-link to="/bilan" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
            <span class="nav-text">Bilan et Graphique</span>
          </router-link>
          
          
          <router-link to="/project" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
            </svg>
            <span class="nav-text">Project</span>
          </router-link>
          
          
          <router-link to="/request" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span class="nav-text">Request</span>
          </router-link>
          
          <router-link to="/onhand" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"></path>
            </svg>
            <span class="nav-text">On hand</span>
          </router-link>
          
          <router-link to="/grn-report" class="nav-item" active-class="nav-item-active">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14,2 14,8 20,8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10,9 9,9 8,9"></polyline>
            </svg>
            <span class="nav-text">GRN Report</span>
          </router-link>
        </div>

        <!-- Boutons en bas de la sidebar -->
        <div class="sidebar-footer">
          <button class="theme-toggle" @click="toggleTheme">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="4"></circle>
              <path d="m12 2 1.02 2.03L16 3l1.03 3.02L20 7l-1.03 3.02L16 11l-3.02-1.03L12 22l-1.02-2.03L8 21l-1.03-3.02L4 17l1.03-3.02L8 13l3.02 1.03L12 2z"></path>
            </svg>
            <span class="theme-text">Light</span>
          </button>
          <button class="theme-toggle theme-dark" @click="toggleTheme">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
            <span class="theme-text">Dark</span>
          </button>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="main-content">
        <router-view></router-view>
      </main>
    </div>
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
const userName = ref('Mathias W.');

// Methods
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  localStorage.setItem('darkMode', isDarkMode.value);
};

const toggleTheme = () => {
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
};

const openNotifications = () => {
  console.log('Opening notifications...');
};

const viewProfile = () => {
  console.log('Viewing profile...');
  showUserMenu.value = false;
};

const logout = () => {
  console.log('Logging out...');
  showUserMenu.value = false;
};

// Close user menu when clicking outside
const closeUserMenu = (event) => {
  if (!event.target.closest('.user-profile') && !event.target.closest('.user-dropdown')) {
    showUserMenu.value = false;
  }
};

// Lifecycle
onMounted(() => {
  const savedDarkMode = localStorage.getItem('darkMode');
  if (savedDarkMode !== null) {
    isDarkMode.value = JSON.parse(savedDarkMode);
  }
  document.addEventListener('click', closeUserMenu);
});

onUnmounted(() => {
  document.removeEventListener('click', closeUserMenu);
});
</script>

<style scoped>
/* CSS Variables for theming */
:root {
  --primary-color: #2563eb;
  --primary-hover: #1d4ed8;
  --secondary-color: #64748b;
  --accent-color: #0ea5e9;
  --bg-light: #ffffff;
  --bg-gray: #f8fafc;
  --bg-sidebar: #f1f5f9;
  --text-dark: #1e293b;
  --text-light: #64748b;
  --text-muted: #94a3b8;
  --border-color: #e2e8f0;
  --sidebar-width: 280px;
  --sidebar-collapsed-width: 60px;
  --header-height: 70px;
}

.dark-mode {
  --bg-light: #0f172a;
  --bg-gray: #1e293b;
  --bg-sidebar: #1e293b;
  --text-dark: #f8fafc;
  --text-light: #cbd5e1;
  --text-muted: #94a3b8;
  --border-color: #334155;
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
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
  color: white;
  height: var(--header-height);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  position: relative;
  z-index: 1000;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo {
  height: 32px;
  width: auto;
}

.app-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.header-right {
  display: flex;
  align-items: center;
}

.header-icons {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  position: relative;
}

.icon-btn {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 6px;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-btn:hover {
  background: rgba(255,255,255,0.1);
}

.icon-btn svg {
  width: 18px;
  height: 18px;
}

.notification-btn {
  position: relative;
}

.notification-badge {
  position: absolute;
  top: 2px;
  right: 2px;
  background: #ef4444;
  color: white;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.user-profile:hover {
  background: rgba(255,255,255,0.1);
}

.user-avatar {
  width: 28px;
  height: 28px;
  background: rgba(255,255,255,0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-avatar svg {
  width: 16px;
  height: 16px;
}

.user-name {
  font-weight: 500;
  font-size: 0.875rem;
}

.dropdown-arrow {
  width: 14px;
  height: 14px;
  transition: transform 0.2s ease;
}

.user-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: var(--bg-light);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
  min-width: 160px;
  z-index: 1001;
  margin-top: 0.5rem;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
  color: var(--text-dark);
  font-size: 0.875rem;
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

/* Sidebar - Style similaire à l'image */
.sidebar {
  width: var(--sidebar-width);
  background: var(--bg-sidebar);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  transition: all 0.3s ease;
  position: relative;
}

.sidebar-header {
  padding: 1.5rem 1rem 1rem;
  border-bottom: 1px solid var(--border-color);
}

.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo-icon {
  width: 32px;
  height: 32px;
  background: var(--primary-color);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.logo-icon svg {
  width: 18px;
  height: 18px;
}

.sidebar-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--text-dark);
}

.nav-menu {
  flex: 1;
  padding: 1rem 0;
  overflow-y: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  text-decoration: none;
  color: var(--text-light);
  transition: all 0.2s ease;
  margin: 0.125rem 0.5rem;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
}

.nav-item:hover {
  background: var(--bg-light);
  color: var(--text-dark);
}

.nav-item-active {
  background: var(--primary-color);
  color: white;
}

.nav-item-active:hover {
  background: var(--primary-hover);
  color: white;
}

.nav-icon {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
}

.nav-text {
  transition: opacity 0.3s ease;
}

.sidebar-footer {
  padding: 1rem;
  border-top: 1px solid var(--border-color);
  display: flex;
  gap: 0.5rem;
}

.theme-toggle {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.5rem;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  background: var(--bg-light);
  color: var(--text-light);
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.75rem;
  font-weight: 500;
}

.theme-toggle:hover {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.theme-toggle svg {
  width: 14px;
  height: 14px;
}

.theme-dark {
  background: var(--text-dark);
  color: white;
  border-color: var(--text-dark);
}

.sidebar-collapsed {
  width: var(--sidebar-collapsed-width);
}

.sidebar-collapsed .nav-text,
.sidebar-collapsed .sidebar-title,
.sidebar-collapsed .theme-text {
  opacity: 0;
  pointer-events: none;
}

.sidebar-collapsed .nav-item {
  justify-content: center;
  padding: 0.75rem;
}

.sidebar-collapsed .sidebar-logo {
  justify-content: center;
}

.sidebar-collapsed .theme-toggle {
  padding: 0.5rem;
}

/* Main content */
.main-content {
  flex: 1;
  padding: 0;
  background: var(--bg-light);
  min-height: calc(100vh - var(--header-height));
  overflow-y: auto;
}

/* Responsive design */
@media (max-width: 768px) {
  .header {
    padding: 0 1rem;
  }
  
  .app-title {
    display: none;
  }
  
  .user-name {
    display: none;
  }
  
  .sidebar {
    width: var(--sidebar-collapsed-width);
  }
  
  .nav-text,
  .sidebar-title,
  .theme-text {
    display: none;
  }
}

/* Smooth transitions */
* {
  transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
}

/* Custom scrollbar */
.nav-menu::-webkit-scrollbar {
  width: 4px;
}

.nav-menu::-webkit-scrollbar-track {
  background: transparent;
}

.nav-menu::-webkit-scrollbar-thumb {
  background: var(--border-color);
  border-radius: 2px;
}

.nav-menu::-webkit-scrollbar-thumb:hover {
  background: var(--text-muted);
}
</style>