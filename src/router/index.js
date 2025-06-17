import { createRouter, createWebHistory } from 'vue-router';
import AjouterProduit from '../components/AjouterProduit.vue';
import ListeProduits from '../components/ListeProduits.vue';
import BilanProduits from '../components/BilanProduits.vue';
import Dashboard from '../components/Dashboard.vue';
import Landing from '../views/Landing.vue';
import copilot from '../components/copilot.vue';

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: Landing,
    meta: { layout: 'none' } // sans header/footer
  },
  { 
    path: '/ajouter', 
    component: AjouterProduit,
    meta: { requiresAuth: true } // Route protégée
  },
  { 
    path: '/lister-modifier', 
    component: ListeProduits,
    meta: { requiresAuth: true } // Route protégée
  },
  { 
    path: '/bilan', 
    component: BilanProduits,
    meta: { requiresAuth: true } // Route protégée
  },
  { 
    path: '/dashboard', 
    component: Dashboard,
    meta: { requiresAuth: true } // Route protégée
  },
  { 
    path: '/copilot', 
    component: copilot,  
    meta: { requiresAuth: true } // Route protégée
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guard - Protection des routes
router.beforeEach((to, from, next) => {
  // Vérifier si la route nécessite une authentification
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Vérifier si l'utilisateur est connecté
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const user = localStorage.getItem('user');
    
    if (!isLoggedIn || !user) {
      // Utilisateur non connecté, rediriger vers Landing avec le chemin de destination
      next({ 
        path: '/', 
        query: { redirect: to.fullPath } 
      });
    } else {
      // Utilisateur connecté, autoriser l'accès
      next();
    }
  } else {
    // Route publique (Landing), autoriser l'accès
    next();
  }
});

export default router;