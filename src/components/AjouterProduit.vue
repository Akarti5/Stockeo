<template>
    <h1>Ajouter un Produit</h1>
  
    <form @submit.prevent="ajouterNouveauProduit">
      <div>
        <label for="design">Désignation:</label>
        <input type="text" id="design" v-model="nouveauProduit.design" required>
      </div>
      <div>
        <label for="prix">Prix:</label>
        <input type="number" id="prix" v-model="nouveauProduit.prix" step="0.01" required>
      </div>
      <div>
        <label for="quantite">Quantité:</label>
        <input type="number" id="quantite" v-model="nouveauProduit.quantite" required>
      </div>
      <button type="submit">Ajouter le Produit</button>
    </form>
  
    <div v-if="message" :class="{'success': isSuccess, 'error': !isSuccess}">
      {{ message }}
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const nouveauProduit = ref({
    design: '',
    prix: null,
    quantite: null,
  });
  
  const message = ref('');
  const isSuccess = ref(false);
  
  const ajouterNouveauProduit = async () => {
    try {
      const response = await fetch('http://localhost/backend/ajouter_produit.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(nouveauProduit.value),
      });
  
      const data = await response.json();
      message.value = data.message;
      isSuccess.value = response.ok;
  
      // Réinitialiser le formulaire en cas de succès
      if (response.ok) {
        nouveauProduit.value = { design: '', prix: null, quantite: null };
      }
    } catch (error) {
      console.error('Erreur lors de l\'ajout du produit:', error);
      message.value = 'Erreur de connexion au serveur.';
      isSuccess.value = false;
    }
  };
  </script>
  
  <style scoped>
  form {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  div {
    margin-bottom: 10px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  
  input[type="text"],
  input[type="number"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 3px;
    box-sizing: border-box;
  }
  
  button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 1rem;
  }
  
  button[type="submit"]:hover {
    background-color: #0056b3;
  }
  
  .success {
    color: green;
    margin-top: 10px;
  }
  
  .error {
    color: red;
    margin-top: 10px;
  }
  </style>