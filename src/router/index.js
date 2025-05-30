import { createRouter, createWebHistory } from 'vue-router';
import AjouterProduit from '../components/AjouterProduit.vue';
import ListeProduits from '../components/ListeProduits.vue';
import BilanProduits from '../components/BilanProduits.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/ajouter', component: AjouterProduit },
    { path: '/lister-modifier', component: ListeProduits },
    { path: '/bilan', component: BilanProduits },
  ],
});

export default router;