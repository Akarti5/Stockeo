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
        <tr v-for="produit in produits" :key="produit.numproduit">
          <td>{{ produit.numproduit }}</td>
          <td>{{ produit.design }}</td>
          <td>{{ produit.prix }}</td>
          <td>{{ produit.quantite }}</td>
          <td>{{ produit.montant }}</td>
          <td>
            <button @click="modifierProduit(produit)">Modifier</button>
            <button @click="supprimerProduit(produit.numproduit)">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>
    <p v-else>Aucun produit trouvé.</p>
  
    <div v-if="produitAModifier">
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
  </template>
  
  <script setup>
import { ref, onMounted } from 'vue';

const message = ref('');
const isSuccess = ref(false);
const produits = ref([]);
const produitAModifier = ref(null);

const fetchProduits = async () => {
  try {
    const response = await fetch('http://localhost/backend/lire_produits.php');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    console.log('Produits récupérés');
    produits.value = data;
  } catch (error) {
    console.error('Erreur lors de la récupération des produits:', error);
    message.value = 'Erreur lors de la récupération des produits.';
    isSuccess.value = false;
  }
};

onMounted(fetchProduits);

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
</script>
  
  <style scoped>
  /* Styles pour le tableau et le formulaire (vous pouvez les personnaliser) */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  th {
    background-color: #f2f2f2;
  }
  
  button {
    padding: 8px 12px;
    margin-right: 5px;
    cursor: pointer;
  }
  
  .success {
    color: green;
    margin-top: 10px;
  }
  
  .error {
    color: red;
    margin-top: 10px;
  }
  
  /* Styles pour le formulaire de modification */
  .modifier-form {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  .modifier-form div {
    margin-bottom: 10px;
  }
  
  .modifier-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  
  .modifier-form input[type="text"],
  .modifier-form input[type="number"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 3px;
    box-sizing: border-box;
  }
  
  .modifier-form button[type="submit"],
  .modifier-form button[type="button"] {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 1rem;
    margin-right: 5px;
  }
  
  .modifier-form button[type="submit"]:hover,
  .modifier-form button[type="button"]:hover {
    background-color: #0056b3;
  }
  </style>