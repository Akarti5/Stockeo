```vue
<template>
  <div class="inventory-container">
    <div class="list-header">
      <h1 class="list-title">Tous les produits</h1>
      <div class="search-and-actions">
        <div class="search-wrapper">
          <i class="fas fa-search search-icon"></i>
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search..."
            class="search-input"
            @keyup.enter="rechercherProduits"
          />
          <button class="action-btn btn-search" @click="rechercherProduits">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <button class="action-btn btn-download">
          <i class="fas fa-download"></i> Download
        </button>
        <button class="action-btn btn-add" @click="afficherFormulaireAjout">
          <i class="fas fa-plus"></i> Add New
        </button>
      </div>
    </div>

    <div v-if="isLoading" class="loading-indicator">
      <p>Chargement en cours...</p>
    </div>

    <div class="table-container" v-if="!isLoading && produits.length > 0">
      <table>
        <thead>
          <tr>
            <th><input type="checkbox" /></th>
            <th>Numero</th>
            <th>Designation</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Montant</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="produit in paginatedProduits" :key="produit.numproduit">
            <td><input type="checkbox" /></td>
            <td>#{{ produit.numproduit }}</td>
            <td>{{ produit.design }}</td>
            <td>{{ produit.prix }}€</td>
            <td>{{ produit.quantite }}</td>
            <td>{{ produit.montant }}€</td>
            <td>
              <button class="icon-btn" @click="modifierProduit(produit)">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </button>
              <button class="icon-btn btn-delete" @click="supprimerProduit(produit.numproduit)">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <p v-else-if="!isLoading">Aucun produit trouvé.</p>

    <div class="list-footer" v-if="!isLoading && produits.length > 0">
        <div class="items-per-page">
            <select v-model="itemsPerPage">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
            </select>
            <span>items per page</span>
        </div>
        <div class="pagination-info">
            {{ paginationInfo }}
        </div>
        <div class="pagination-controls">
            <button class="pagination-arrow" :disabled="currentPage === 1" @click="previousPage">
                <i class="fa-chevron-left fas"></i>
            </button>
            <button
                v-for="page in totalPages"
                :key="page"
                class="pagination-number"
                :class="{ active: currentPage === page }"
                @click="goToPage(page)"
            >
                {{ page }}
            </button>
            <button class="pagination-arrow" :disabled="currentPage === totalPages" @click="nextPage">
                <i class="fa-chevron-right fas"></i>
            </button>
        </div>
    </div>

    <div class="form-container" v-if="produitAModifier">
        <div class="modifier-form">
            <h2>Modifier le Produit #{{ produitAModifier.numproduit }}</h2>
            <form @submit.prevent="enregistrerModification">
                <div>
                    <label for="design-modifier">Désignation:</label>
                    <input type="text" id="design-modifier" v-model="produitAModifier.design" required>
                </div>
                <div>
                    <label for="prix-modifier">Prix:</label>
                    <input type="number" id="prix-modifier" v-model="produitAModifier.prix" step="0.01" required>
                </div>
                <div>
                    <label for="quantite-modifier">Quantité:</label>
                    <input type="number" id="quantite-modifier" v-model="produitAModifier.quantite" required>
                </div>
                <button type="submit">Enregistrer les Modifications</button>
                <button type="button" @click="annulerModification">Annuler</button>
            </form>
        </div>
    </div>

    <div class="form-container" v-if="showAddForm">
      <div class="modifier-form">
        <h2>Ajouter un Produit</h2>
        <form @submit.prevent="ajouterProduit">
          <div>
            <label for="design-ajouter">Désignation:</label>
            <input type="text" id="design-ajouter" v-model="nouveauProduit.design" required>
          </div>
          <div>
            <label for="prix-ajouter">Prix:</label>
            <input type="number" id="prix-ajouter" v-model="nouveauProduit.prix" step="0.01" required>
          </div>
          <div>
            <label for="quantite-ajouter">Quantité:</label>
            <input type="number" id="quantite-ajouter" v-model="nouveauProduit.quantite" required>
          </div>
          <button type="submit">Ajouter le Produit</button>
          <button type="button" @click="annulerAjout">Annuler</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Swal from 'sweetalert2';

const produits = ref([]);
const produitAModifier = ref(null);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const searchQuery = ref('');
const isLoading = ref(false);

// NOUVEAU : États pour le formulaire d'ajout
const showAddForm = ref(false);
const nouveauProduit = ref({
  design: '',
  prix: null,
  quantite: null,
});

// Récupération initiale des produits
const fetchProduits = async () => {
  try {
    isLoading.value = true;
    const response = await fetch('http://localhost/backend/lire_produits.php');
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    const data = await response.json();
    produits.value = data;
    currentPage.value = 1;
  } catch (error) {
    console.error('Erreur lors de la récupération:', error);
    await Swal.fire({ icon: 'error', title: 'Erreur!', text: 'Erreur lors de la récupération des produits.' });
  } finally {
    isLoading.value = false;
  }
};

onMounted(fetchProduits);

// --- Fonctions de Recherche et Rafraîchissement ---
const rechercherProduits = async () => {
  try {
    isLoading.value = true;
    const termeCleaned = searchQuery.value.trim();
    if (!termeCleaned) {
      await fetchProduits();
      return;
    }
    const url = `http://localhost/backend/lire_produits.php?search=${encodeURIComponent(termeCleaned)}`;
    const response = await fetch(url);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    const data = await response.json();
    produits.value = data;
    if (data.length === 0) {
      await Swal.fire({ icon: 'info', title: 'Aucun résultat', text: `Aucun produit trouvé pour "${termeCleaned}"` });
    }
    currentPage.value = 1;
  } catch (error) {
    console.error('Erreur lors de la recherche:', error);
    await Swal.fire({ icon: 'error', title: 'Erreur!', text: 'Erreur lors de la recherche des produits.' });
  } finally {
    isLoading.value = false;
  }
};

// --- Logique d'AJOUT de produit ---
const afficherFormulaireAjout = () => {
  showAddForm.value = true;
};

const annulerAjout = () => {
  showAddForm.value = false;
  nouveauProduit.value = { design: '', prix: null, quantite: null };
};

const ajouterProduit = async () => {
  try {
    const response = await fetch('http://localhost/backend/ajouter_produit.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(nouveauProduit.value),
    });
    const data = await response.json();
    if (response.ok) {
      await Swal.fire({
        icon: 'success',
        title: 'Succès!',
        text: data.message || 'Produit ajouté avec succès!',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
      });
      annulerAjout();
      await fetchProduits();
    } else {
      await Swal.fire({ icon: 'error', title: 'Erreur!', text: data.message || "Erreur lors de l'ajout." });
    }
  } catch (error) {
    console.error("Erreur lors de l'ajout:", error);
    await Swal.fire({ icon: 'error', title: 'Erreur de Connexion!', text: 'Impossible de se connecter au serveur.' });
  }
};

// --- Logique de MODIFICATION de produit ---
const modifierProduit = (produit) => {
  produitAModifier.value = { ...produit };
};

const annulerModification = () => {
  produitAModifier.value = null;
};

const enregistrerModification = async () => {
  if (produitAModifier.value) {
    try {
      const response = await fetch('http://localhost/backend/modifier_produit.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(produitAModifier.value),
      });
      const data = await response.json();
      if (response.ok) {
        const index = produits.value.findIndex(p => p.numproduit === produitAModifier.value.numproduit);
        if (index !== -1) {
          const montant = produitAModifier.value.prix * produitAModifier.value.quantite;
          produits.value[index] = { ...produitAModifier.value, montant };
        }
        produitAModifier.value = null;
        await Swal.fire({ icon: 'success', title: 'Succès!', text: data.message || 'Produit modifié', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
      } else {
        await Swal.fire({ icon: 'error', title: 'Erreur!', text: data.message || 'Erreur modification' });
      }
    } catch (error) {
      console.error('Erreur lors de la modification:', error);
      await Swal.fire({ icon: 'error', title: 'Erreur!', text: 'Erreur lors de la modification du produit.' });
    }
  }
};

// --- Logique de SUPPRESSION de produit ---
const supprimerProduit = async (id) => {
  if (id === undefined) return;
  const produit = produits.value.find(p => p.numproduit === id);
  const result = await Swal.fire({
    title: 'Êtes-vous sûr?',
    html: `Vous allez supprimer :<br><strong>"${produit ? produit.design : `ID ${id}`}"</strong>`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Oui, supprimer!',
    cancelButtonText: 'Annuler',
  });

  if (result.isConfirmed) {
    try {
      const response = await fetch(`http://localhost/backend/supprimer_produit.php?id=${id}`, { method: 'DELETE' });
      const data = await response.json();
      if (response.ok) {
        await Swal.fire({ icon: 'success', title: 'Supprimé!', text: data.message || 'Produit supprimé.', timer: 2000, showConfirmButton: false });
        await fetchProduits();
      } else {
        await Swal.fire({ icon: 'error', title: 'Erreur!', text: data.message || 'Erreur suppression.' });
      }
    } catch (error) {
      console.error('Erreur lors de la suppression:', error);
      await Swal.fire({ icon: 'error', title: 'Erreur!', text: 'Erreur lors de la suppression du produit.' });
    }
  }
};

// --- Propriétés COMPUTED et Méthodes de PAGINATION ---
const paginatedProduits = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + Number(itemsPerPage.value);
  return produits.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(produits.value.length / itemsPerPage.value);
});

const paginationInfo = computed(() => {
  const total = produits.value.length;
  if (total === 0) return '';
  const start = (currentPage.value - 1) * itemsPerPage.value + 1;
  const end = Math.min(start + Number(itemsPerPage.value) - 1, total);
  return `Showing ${start} - ${end} of ${total}`;
});

const previousPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const goToPage = (page) => {
  currentPage.value = page;
};
</script>

<style scoped>
/* Polices et variables */
:root {
  --primary-color: #3B82F6;
  --text-color-dark: #1E293B;
  --text-color-light: #64748B;
  --border-color: #E2E8F0;
  --background-color: #F8FAFC;
  --white: #FFFFFF;
  --danger-color: #EF4444;
  --success-color: #10B981;
  --shadow-color: rgba(0, 0, 0, 0.1);
}

.inventory-container {
  padding: 24px;
  background-color: var(--background-color);
  font-family: 'Inter', sans-serif;
}

/* En-tête */
.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.list-title {
  font-size: 24px;
  font-weight: 600;
  color: var(--text-color-dark);
}

.search-and-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

.search-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  gap: 8px;
}

.search-icon {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  color: var(--text-color-light);
}

.search-input {
  padding: 10px 12px 10px 36px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  width: 200px;
  transition: border-color 0.2s;
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-color);
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s, transform 0.2s;
}

.btn-search {
  background-color: var(--success-color);
  color: var(--white);
}

.btn-search:hover {
  background-color: #059669;
  transform: translateY(-2px);
}

.btn-download {
  background-color: var(--white);
  color: var(--text-color-dark);
  border: 1px solid var(--border-color);
}

.btn-download:hover {
  background-color: #f1f5f9;
  transform: translateY(-2px);
}

.btn-add {
  background-color: var(--primary-color);
  color: var(--white);
}

.btn-add:hover {
  background-color: #2563eb;
  transform: translateY(-2px);
}

/* Tableau */
.table-container {
  background-color: var(--white);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 16px;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
  color: var(--text-color-dark);
  font-size: 14px;
}

thead th {
  background-color: #F9FAFB;
  font-weight: 500;
  color: rgb(3, 3, 61);
  text-transform: uppercase;
  font-size: 12px;
}

tbody tr:last-child td {
  border-bottom: none;
}

.icon-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-color-light);
  padding: 4px;
  margin-right: 8px;
  font-size: 16px;
}

.icon-btn:hover {
  color: var(--primary-color);
}

.icon-btn.btn-delete:hover {
  color: var(--danger-color);
}

/* input[type="checkbox"] {
  width: 16px;
  height: 16px;
  border-radius: 4px;
} */

/* Pied de page et Pagination */
.list-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 24px;
  color: var(--text-color-light);
  font-size: 14px;
}

.items-per-page {
  display: flex;
  align-items: center;
  gap: 8px;
}

.items-per-page select {
  padding: 4px 8px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 4px;
}

.pagination-arrow,
.pagination-number {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 32px;
  height: 32px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  background-color: var(--white);
  cursor: pointer;
  transition: all 0.2s;
}

.pagination-arrow:disabled {
  color: var(--border-color);
  cursor: not-allowed;
}

.pagination-number.active {
  background-color: var(--primary-color);
  color: var(--white);
  border-color: var(--primary-color);
}

.pagination-number:hover:not(.active) {
  background-color: #f1f5f9;
}

/* Indicateur de chargement */
.loading-indicator {
  text-align: center;
  padding: 40px;
  font-size: 1.2rem;
  color: var(--primary-color);
}

/* Conteneur de formulaire */
.form-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

/* Style partagé pour les formulaires d'ajout/modification */
.modifier-form {
  background-color: rgba(0, 0, 0, 0.5); /* Fond semi-transparent */
  backdrop-filter: blur(8px); /* Flou de l'arrière-plan */
  -webkit-backdrop-filter: blur(8px);
  padding: 30px;
  border-radius: 20px;
  width: 90%;
  max-width: 600px;
  box-shadow: 0 10px 30px var(--shadow-color);
  animation: fadeInUp 0.3s ease-out;
  transform: translateY(0);
  transition: transform 0.3s ease, opacity 0.3s ease;
}

/* Animation d'apparition */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modifier-form h2 {
  margin-top: 0;
  margin-bottom: 25px;
  color: var(--text-color-dark);
  font-size: 24px;
  font-weight: 700;
  position: relative;
  text-align: center;
  padding-bottom: 10px;
}

/* Soulignement animé pour le titre */
.modifier-form h2::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 50px;
  height: 3px;
  background-color: var(--primary-color);
  border-radius: 2px;
  transition: width 0.3s ease;
}

.modifier-form h2:hover::after {
  width: 100px;
}

.modifier-form div {
  margin-bottom: 20px;
  position: relative;
}

.modifier-form label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: var(--text-color-light);
  font-size: 14px;
  transition: color 0.3s ease;
}

.modifier-form input {
  width: 100%;
  padding: 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
  box-sizing: border-box;
  color: black;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.modifier-form input:focus {
  border-color: var(--primary-color);
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modifier-form input:hover {
  border-color: var(--primary-color);
}

.modifier-form button {
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 500;
  font-size: 14px;
  margin-right: 12px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.modifier-form button[type="submit"] {
  background-color: #cbd5e1;
  color: black;
}

.modifier-form button[type="submit"]:hover {
  background-color: #2563eb;
  transform: translateY(-2px);
}

.modifier-form button[type="button"] {
  background-color: #cbd5e1;
  color: black;
}

.modifier-form button[type="button"]:hover {
  background-color: #cbd5e1;
  transform: translateY(-2px);
}

.modifier-form button[type="submit"]:active {
  transform: scale(0.95);
}
</style>
```