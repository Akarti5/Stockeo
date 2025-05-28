<template>
  <h1>Liste et Mise à Jour des Produits</h1>

  <div v-if="message" :class="{'success': isSuccess, 'error': !isSuccess}">
    {{ message }}
  </div>

  <table v-if="produits.length > 0">
    <thead>
      <tr>
        <th>Numéro</th>
        <th>Désignation</th>
        <th>Prix</th>
        <th>Quantité</th>
        <th>Montant</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="produit in paginatedProduits" :key="produit.numproduit">
        <td>{{ produit.numproduit }}</td>
        <td>{{ produit.design }}</td>
        <td>{{ produit.prix }}</td>
        <td>{{ produit.quantite }}</td>
        <td>{{ produit.montant }}</td>
        <td>
          <button class="btn-modifier" @click="modifierProduit(produit)">Modifier</button>
          <button class="btn-supprimer" @click="supprimerProduit(produit.numproduit)">Supprimer</button>
        </td>
      </tr>
    </tbody>
  </table>
  <p v-else>Aucun produit trouvé.</p>

  <!-- Pagination Controls -->
  <div class="pagination" v-if="produits.length > 0">
    <button class="btn-pagination" :disabled="currentPage === 1" @click="previousPage">Précédent</button>
    <button class="btn-pagination" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }" @click="goToPage(page)">
      {{ page }}
    </button>
    <button class="btn-pagination" :disabled="currentPage === totalPages" @click="nextPage">Suivant</button>
  </div>

  <div class="form-container" v-if="produitAModifier">
    <div class="modifier-form">
      <h2>Modifier le Produit {{ produitAModifier.numproduit }}</h2>
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
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const message = ref('');
const isSuccess = ref(false);
const produits = ref([]);
const produitAModifier = ref(null);
const currentPage = ref(1);
const itemsPerPage = 10;

const fetchProduits = async () => {
  try {
    const response = await fetch('http://localhost/backend/lire_produits.php');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    console.log('Produits récupérés');
    produits.value = data;
    // Reset to first page after fetching new data
    currentPage.value = 1;
  } catch (error) {
    console.error('Erreur lors de la récupération des produits:', error);
    message.value = 'Erreur lors de la récupération des produits.';
    isSuccess.value = false;
  }
};

onMounted(fetchProduits);

// Compute paginated products
const paginatedProduits = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return produits.value.slice(start, end);
});

// Compute total number of pages
const totalPages = computed(() => {
  return Math.ceil(produits.value.length / itemsPerPage);
});

const modifierProduit = (produit) => {
  produitAModifier.value = { ...produit };
};

const enregistrerModification = async () => {
  if (produitAModifier.value) {
    try {
      const response = await fetch('http://localhost/backend/modifier_produit.php', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(produitAModifier.value),
      });

      const data = await response.json();
      message.value = data.message;
      isSuccess.value = response.ok;

      if (response.ok) {
        await fetchProduits();
        produitAModifier.value = null;
      }
    } catch (error) {
      console.error('Erreur lors de la modification du produit:', error);
      message.value = 'Erreur lors de la modification du produit.';
      isSuccess.value = false;
    }
  }
};

const supprimerProduit = async (id) => {
  if (id === undefined) {
    console.error("L'ID du produit est undefined");
    message.value = "Impossible de supprimer le produit : ID manquant";
    isSuccess.value = false;
    return;
  }
  if (confirm(`Êtes-vous sûr de vouloir supprimer le produit avec l'ID ${id} ?`)) {
    try {
      const response = await fetch(`http://localhost/backend/supprimer_produit.php?id=${id}`, {
        method: 'DELETE',
      });

      const data = await response.json();
      message.value = data.message;
      isSuccess.value = response.ok;

      if (response.ok) {
        await fetchProduits();
      }
    } catch (error) {
      console.error('Erreur lors de la suppression du produit:', error);
      message.value = 'Erreur lors de la suppression du produit.';
      isSuccess.value = false;
    }
  }
};

const annulerModification = () => {
  produitAModifier.value = null;
};

// Pagination methods
const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const goToPage = (page) => {
  currentPage.value = page;
};
</script>

<style scoped>
/* Style global pour la page */
h1 {
  text-align: center;
  font-size: 2.2rem;
  color: #1a3c5e;
  margin-bottom: 30px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Styles pour les messages de succès et d'erreur */
.success, .error {
  max-width: 600px;
  margin: 20px auto;
  padding: 15px 20px;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 500;
  text-align: center;
  transition: opacity 0.3s ease;
}

.success {
  background-color: #ffffff;
  color: #058f0c;
  border: 1px solid #a5d6a7;
  border-radius: 15px;
}

.error {
  background-color: #fce4e4;
  color: #d32f2f;
  border: 1px solid #ef9a9a;
}

/* Styles pour le tableau */
table {
  max-width: 1200px;
  width: 100%;
  margin: 30px auto;
  border-collapse: separate;
  border-spacing: 0;
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

th, td {
  padding: 15px 20px;
  text-align: left;
  font-size: 1rem;
  border-bottom: 1px solid #e0e0e0;
}

th {
  background-color: #8BB5D6 ;
  color: #ffffff;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

td {
  color: #1a096a;
}

tbody tr {
  transition: background-color 0.3s ease;
}

tbody tr:hover {
  background-color: #f5f7fa;
}

/* Boutons dans le tableau */
.btn-modifier, .btn-supprimer {
  padding: 10px 18px;
  border: none;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

.btn-modifier {
  background-color: #60A5FA ;
  color: #ffffff;
  margin-right: 10px;
}

.btn-modifier:hover {
  background-color: #3B82F6;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
}

.btn-supprimer {
  background-color: #FB7185 ;
  color: #ffffff;
}

.btn-supprimer:hover {
  background-color: #F43F5E;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(255, 77, 79, 0.3);
}

.btn-modifier:active, .btn-supprimer:active {
  transform: translateY(0);
  box-shadow: none;
}

/* Message "Aucun produit trouvé" */
p {
  text-align: center;
  font-size: 1.2rem;
  color: #666;
  margin-top: 30px;
  font-style: italic;
}

/* Conteneur pour centrer le formulaire */
.form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
  padding: 20px;
  box-sizing: border-box;
}

/* Styles pour le formulaire de modification */
.modifier-form {
  max-width: 600px;
  width: 100%;
  padding: 40px;
  background:#FAFAFA;
  border: 1px solid #e0e0e0;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.modifier-form:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
}

.modifier-form h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 1.8rem;
  color: #1a3c5e;
  font-weight: 600;
}

.modifier-form div {
  margin-bottom: 25px;
}

.modifier-form label {
  display: block;
  margin-bottom: 10px;
  font-weight: 600;
  font-size: 1.15rem;
  color: #1a3c5e;
}

.modifier-form input[type="text"],
.modifier-form input[type="number"] {
  width: 100%;
  padding: 14px 18px;
  border: 2px solid #d0d8e0;
  border-radius: 10px;
  background-color: #f9fbfd;
  font-size: 1.05rem;
  color: #2c3e50;
  box-sizing: border-box;
  transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
}

.modifier-form input[type="text"]:focus,
.modifier-form input[type="number"]:focus {
  border-color: #1a73e8;
  box-shadow: 0 0 10px rgba(26, 115, 232, 0.3);
  background-color: #ffffff;
  outline: none;
}

.modifier-form button[type="submit"],
.modifier-form button[type="button"] {
  padding: 14px 30px;
  border: none;
  border-radius: 10px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

.modifier-form button[type="submit"] {
  background-color: #1a73e8;
  color: #ffffff;
}

.modifier-form button[type="submit"]:hover {
  background-color: #155ab2;
  transform: translateY(-3px);
  box-shadow: 0 4px 10px rgba(26, 115, 232, 0.4);
  /* margin-left: 100px; */
}

.modifier-form button[type="button"] {
  background-color: #ff4d4f;
  color: #ffffff;
  margin-left: 5px;
}

.modifier-form button[type="button"]:hover {
  background-color: #d93638;
  transform: translateY(-3px);
  box-shadow: 0 4px 10px rgba(255, 77, 79, 0.4);
}

.modifier-form button:active {
  transform: translateY(0);
  box-shadow: none;
}

/* Styles pour la pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin: 20px 0;
}

.btn-pagination {
  padding: 10px 15px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  background-color: #0d0063;
  color: #fffeff;
  transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

.btn-pagination:hover {
  background-color: #1f676f;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
}

.btn-pagination:active {
  transform: translateY(0);
  box-shadow: none;
}

.btn-pagination:disabled {
  background-color: #b0bec5;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-pagination.active {
  background-color: #1a73e8;
  font-weight: 700;
}

.btn-pagination.active:hover {
  background-color: #155ab2;
}
</style>