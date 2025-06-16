<template>
  <div>
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

    <!-- Success/Error Modal -->
    <div v-if="showResultModal" class="modal-overlay" @click="closeResultModal">
      <div class="modal-content" @click.stop>
        <div class="modal-icon">
          <div class="icon-circle" :class="isSuccess ? 'success' : 'error'">
            <i class="icon-result" :class="isSuccess ? 'icon-check' : 'icon-x'">
              {{ isSuccess ? '✓' : '✕' }}
            </i>
          </div>
        </div>
        <h3 class="modal-title">{{ isSuccess ? 'Succès!' : 'Erreur!' }}</h3>
        <p class="modal-text">{{ message }}</p>
        <div class="modal-buttons">
          <button class="btn-ok" @click="closeResultModal">OK</button>
        </div>
      </div>
    </div>

    <!-- Loading overlay -->
    <div v-if="isLoading" class="loading-overlay">
      <div class="loading-spinner"></div>
      <p>Ajout en cours...</p>
    </div>
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
const showResultModal = ref(false);
const isLoading = ref(false);

const closeResultModal = () => {
  showResultModal.value = false;
};

const ajouterNouveauProduit = async () => {
  isLoading.value = true;
  
  try {
    // Vérifier d'abord isLoggedIn
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    console.log("isLoggedIn:", isLoggedIn);
    
    if (isLoggedIn !== 'true' && isLoggedIn !== 'True') {
      message.value = 'Utilisateur non connecté. Veuillez vous connecter.';
      isSuccess.value = false;
      showResultModal.value = true;
      isLoading.value = false;
      return;
    }

    // Récupérer et parser l'utilisateur
    const userString = localStorage.getItem('user');
    console.log("user string:", userString);
    
    if (!userString || userString === 'undefined') {
      message.value = 'Données utilisateur non trouvées. Veuillez vous reconnecter.';
      isSuccess.value = false;
      showResultModal.value = true;
      isLoading.value = false;
      return;
    }

    let user;
    try {
      user = JSON.parse(userString);
      console.log("user object:", user);
    } catch (parseError) {
      console.error("Erreur parsing JSON:", parseError);
      message.value = 'Erreur lors de la lecture des données utilisateur.';
      isSuccess.value = false;
      showResultModal.value = true;
      isLoading.value = false;
      return;
    }

    const userId = user?.id;
    console.log("userId:", userId, "type:", typeof userId);
    
    if (!userId || userId === 'undefined') {
      message.value = 'ID utilisateur non trouvé. Veuillez vous reconnecter.';
      isSuccess.value = false;
      showResultModal.value = true;
      isLoading.value = false;
      return;
    }

    // Préparer les données du produit
    const produitData = {
      design: nouveauProduit.value.design,
      prix: parseFloat(nouveauProduit.value.prix),
      quantite: parseInt(nouveauProduit.value.quantite),
      user_id: parseInt(userId)
    };

    console.log('Données envoyées:', produitData);

    const response = await fetch('http://localhost/backend/ajouter_produit.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        // CORRECTION: Ajouter l'Authorization header
        'Authorization': `Bearer ${userId}`
      },
      body: JSON.stringify(produitData),
    });

    const data = await response.json();
    console.log('Réponse du serveur:', data);
    
    message.value = data.message || 'Réponse du serveur';
    isSuccess.value = data.success || response.ok;

    // Réinitialiser le formulaire en cas de succès
    if (data.success || response.ok) {
      nouveauProduit.value = { design: '', prix: null, quantite: null };
    }
  } catch (error) {
    console.error('Erreur lors de l\'ajout du produit:', error);
    message.value = 'Erreur de connexion au serveur.';
    isSuccess.value = false;
  } finally {
    isLoading.value = false;
    showResultModal.value = true;
  }
};
</script>
<style scoped>
/* Styles pour le formulaire */
div {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input {
  width: 100%;
  max-width: 300px;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

/* Styles pour la modal */
.modal-overlay {
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

.modal-content {
  background-color: white;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
  max-width: 400px;
  width: 90%;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-icon {
  margin-bottom: 20px;
}

.icon-circle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  font-size: 30px;
  font-weight: bold;
}

.icon-circle.success {
  background-color: #d4edda;
  color: #155724;
}

.icon-circle.error {
  background-color: #f8d7da;
  color: #721c24;
}

.modal-title {
  margin: 0 0 15px 0;
  font-size: 1.5em;
}

.modal-text {
  margin-bottom: 20px;
  color: #666;
}

.btn-ok {
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.btn-ok:hover {
  background-color: #0056b3;
}

/* Styles pour le loading */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1001;
  color: white;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-left: 4px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 15px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
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
  border: #60ebe0;
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
  color: #ffffff;
  margin-bottom: 30px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
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
  color: #a2c5eb;
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

/* Custom SweetAlert Modal Styles */
.modal-overlay {
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
  animation: fadeIn 0.3s ease;
}

.modal-content {
  background: white;
  border-radius: 20px;
  padding: 40px 30px;
  text-align: center;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  max-width: 400px;
  width: 90%;
  animation: slideUp 0.3s ease;
  position: relative;
}

.modal-icon {
  margin-bottom: 25px;
}

.icon-circle {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  border: 4px solid #f39c12;
  background-color: #fff3cd;
  animation: pulse 1s infinite;
}

.icon-circle.success {
  border-color: #28a745;
  background-color: #d1e7dd;
}

.icon-circle.error {
  border-color: #dc3545;
  background-color: #f8d7da;
}

.icon-question {
  font-size: 2.5rem;
  font-weight: bold;
  color: #f39c12;
}

.icon-result {
  font-size: 2.5rem;
  font-weight: bold;
}

.icon-check {
  color: #28a745;
}

.icon-x {
  color: #dc3545;
}

.modal-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 15px;
}

.modal-text {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 30px;
  line-height: 1.5;
}

.modal-buttons {
  display: flex;
  gap: 15px;
  justify-content: center;
}

.btn-cancel, .btn-confirm, .btn-ok {
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 100px;
}

.btn-cancel {
  background-color: #6c757d;
  color: white;
}

.btn-cancel:hover {
  background-color: #5a6268;
  transform: translateY(-2px);
}

.btn-confirm {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
}

.btn-confirm:hover {
  background: linear-gradient(135deg, #218838, #1ea688);
  transform: translateY(-2px);
}

.btn-ok {
  background: linear-gradient(135deg, #007bff, #00c4b4);
  color: white;
}

.btn-ok:hover {
  background: linear-gradient(135deg, #0056b3, #009b93);
  transform: translateY(-2px);
}

/* Loading overlay */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1001;
  color: white;
  font-size: 1.2rem;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top: 4px solid #00c4b4;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive design */
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

  .modal-content {
    padding: 30px 20px;
    margin: 20px;
  }

  .modal-buttons {
    flex-direction: column;
  }

  .btn-cancel, .btn-confirm, .btn-ok {
    width: 100%;
  }
}
</style>