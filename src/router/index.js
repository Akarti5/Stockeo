import { createRouter, createWebHistory } from 'vue-router';
import Accueil from '../components/accueil.vue'
import AjouterProduit from '../components/AjouterProduit.vue';
import ListeProduits from '../components/ListeProduits.vue';
import BilanProduits from '../components/BilanProduits.vue';
import Dashboard from '../components/Dashboard.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {path: '/',redirect: '/accueil'},  
    { path: '/ajouter', component: AjouterProduit },
    { path: '/lister-modifier', component: ListeProduits },
    { path: '/bilan', component: BilanProduits },
    { path: '/accueil', component: Accueil },
    { path: '/dashboard' , component: Dashboard}
  ],
});

export default router;