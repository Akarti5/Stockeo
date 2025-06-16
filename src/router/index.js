import { createRouter, createWebHistory } from 'vue-router';
import AjouterProduit from '../components/AjouterProduit.vue';
import ListeProduits from '../components/ListeProduits.vue';
import BilanProduits from '../components/BilanProduits.vue';
import Dashboard from '../components/Dashboard.vue';
import Landing from '../views/Landing.vue'; // importer Landing

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: Landing,
    meta: { layout: 'none' } // 👈 sans header/footer
  },
  { path: '/ajouter', component: AjouterProduit },
  { path: '/lister-modifier', component: ListeProduits },
  { path: '/bilan', component: BilanProduits },
  { path: '/dashboard', component: Dashboard },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
