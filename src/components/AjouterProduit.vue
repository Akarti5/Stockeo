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
/* Conteneur global */
form {
max-width: 550px;
margin: 50px auto;
padding: 35px;
background: transparent; 
border-radius: 15px;
box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
transition: transform 0.3s ease, box-shadow 0.3s ease;
position: relative;
overflow: hidden;
}

/* Animation au survol du formulaire */
form:hover {
transform: translateY(-5px);
box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
}

/* Style des titres */
h1 {
text-align: center;
font-size: 2.2rem;
color:mediumblue;
margin-bottom: 40px;
font-family: 'Poppins', sans-serif;
letter-spacing: 1px;
position: relative;
}

/* Ligne décorative sous le titre */
h1::after {
content: '';
display: block;
width: 80px;
height: 3px;
background: linear-gradient(90deg, #007bff, #00c4b4);
margin: 10px auto;
}

/* Style des groupes d'input */
div {
margin-bottom: 25px;
position: relative;
}


label {
display: block;
margin-bottom: 10px;
font-weight: 600;
color: #043e7b;
font-size: 1.15rem;
transition: color 0.3s ease;
}


input[type="text"],
input[type="number"] {
width: 100%;
padding: 12px 12px 12px 40px;
border: 2px solid #e2e8f0;
border-radius: 10px;
font-size: 1rem;
background-color: #f9fafb;
transition: all 0.3s ease;
box-sizing: border-box;
}


input[type="text"]:focus,
input[type="number"]:focus {
outline: none;
border-color: #00c4b4;
background-color: #fff;
box-shadow: 0 0 10px rgba(0, 196, 180, 0.3);
}


div:nth-child(1) input::before {
content: '📝';
position: absolute;
left: 12px;
top: 50%;
transform: translateY(-50%);
font-size: 1.2rem;
}

div:nth-child(2) input::before {
content: '💰';
position: absolute;
left: 12px;
top: 50%;
transform: translateY(-50%);
font-size: 1.2rem;
}

div:nth-child(3) input::before {
content: '🔢';
position: absolute;
left: 12px;
top: 50%;
transform: translateY(-50%);
font-size: 1.2rem;
}


button[type="submit"] {
width: 100%;
background: linear-gradient(135deg, #007bff, #00c4b4);
color: white;
padding: 14px;
border: none;
border-radius: 10px;
cursor: pointer;
font-size: 1.2rem;
font-weight: 700;
text-transform: uppercase;
letter-spacing: 1px;
transition: all 0.3s ease;
position: relative;
overflow: hidden;
}


button[type="submit"]::after {
content: '';
position: absolute;
top: 50%;
left: 50%;
width: 0;
height: 0;
background: rgba(255, 255, 255, 0.3);
border-radius: 50%;
transform: translate(-50%, -50%);
transition: width 0.6s ease, height 0.6s ease;
}

button[type="submit"]:hover::after {
width: 300px;
height: 300px;
}

button[type="submit"]:hover {
background: linear-gradient(135deg, #0056b3, #009b93);
transform: translateY(-3px);
}

button[type="submit"]:active {
transform: translateY(0);
}


.success {
margin-top: 25px;
padding: 12px;
background-color: #d1fae5;
color: #065f46;
border-radius: 10px;
text-align: center;
font-weight: 500;
animation: fadeIn 0.5s ease;
}

.error {
margin-top: 25px;
padding: 12px;
background-color: #fee2e2;
color: #991b1b;
border-radius: 10px;
text-align: center;
font-weight: 500;
animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
from {
  opacity: 0;
  transform: translateY(10px);
}
to {
  opacity: 1;
  transform: translateY(0);
}
}


@media (max-width: 600px) {
form {
  margin: 20px;
  padding: 25px;
}

h1 {
  font-size: 1.9rem;
}

button[type="submit"] {
  font-size: 1.1rem;
  padding: 12px;
}

input[type="text"],
input[type="number"] {
  padding: 10px 10px 10px 35px;
  font-size: 0.95rem;
}
}
</style>