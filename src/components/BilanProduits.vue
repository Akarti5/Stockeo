<template>
  <h1>Bilan des Produits</h1>
  
  <div class="parent">
    <div class="div1">
      <div class="title">
        Total des Produits
      </div>
      <div class="value">
        {{ totalProduits }} 
      </div>
    </div>

    <div class="div2">
      <div class="title">
        Total des Montants
      </div>
      <div class="value">
        {{ totalMontant }} &euro;
      </div>
    </div>

    <div class="div3">
      <div class="title">
        Montant maximum
      </div>
      <div class="value">
        {{ montantMaximal }} &euro;
      </div>
    </div>

    <div class="div4">
      <div class="title">
        Montant minimum 
      </div>
      <div class="value">
        {{ montantMinimal }} &euro;
      </div>
    </div>

    <div class="div5">
      <h3>Statistiques des produits</h3>
      <canvas id="produitsChart" width="400" height="300"></canvas>
    </div>
  </div>
</template>
  
<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Chart from 'chart.js/auto';

// Variables pour le graphique
let produitsChart = null;

// Variables de données
const totalProduits = ref(0);
const totalMontant = ref(0);
const montantMaximal = ref(0);
const montantMinimal = ref(0);
const allProduits = ref([]);

// Fonction pour récupérer les valeurs totales
const fetchTotalProduits = async () => {
  try {
    const response = await axios.get('http://localhost/backend/bilan_produits.php');
    if (response.data && response.data !== undefined) {
      totalProduits.value = response.data.totalProduits;
      totalMontant.value = response.data.totalMontant;
      montantMaximal.value = response.data.montantMaximal;
      montantMinimal.value = response.data.montantMinimal;
      allProduits.value = response.data.produits || [];
      
      // Création du graphique après avoir récupéré les données
      createChart();
    } else {
      console.error('Unexpected API response:', response.data);
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des produits:', error);
  }
};
  
// Fonction pour créer le graphique avec tous les produits
const createChart = () => {
  // Destruction du graphique existant s'il existe
  if (produitsChart) {
    produitsChart.destroy();
  }

  // Obtenir le contexte du canvas
  const ctx = document.getElementById('produitsChart');
  if (!ctx) return;

  // Préparer les données pour le graphique
  const labels = allProduits.value.map(produit => produit.design);
  const montants = allProduits.value.map(produit => produit.montant);
  
  // Déterminer les couleurs - mettre en évidence les valeurs min et max
  const backgroundColors = montants.map(montant => {
    if (montant === montantMinimal.value) {
      return 'rgba(255, 187, 51, 0.8)'; // Bleu pour le min
    } else if (montant === montantMaximal.value) {
      return 'rgba(255, 99, 132, 0.8)'; // Rouge pour le max
    } else {
      return 'rgba(75, 192, 192, 0.6)'; // Couleur standard pour les autres
    }
  });

  const borderColors = backgroundColors.map(color => color.replace('0.8', '1').replace('0.6', '0.8'));

  // Créer le graphique
  produitsChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Montants des produits',
        data: montants,
        backgroundColor: backgroundColors,
        borderColor: borderColors,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Montant (€)'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Produits'
          },
          ticks: {
            autoSkip: false,
            maxRotation: 90,
            minRotation: 45
          }
        }
      },
      plugins: {
        legend: {
          display: true,
          position: 'top'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              if (context.parsed.y !== null) {
                label += context.parsed.y + ' €';
              }
              return label;
            },
            afterLabel: function(context) {
              const produit = allProduits.value[context.dataIndex];
              if (produit) {
                return `Prix: ${produit.prix}€ × Quantité: ${produit.quantite}`;
              }
              return '';
            }
          }
        }
      }
    }
  });
};

// Surveiller les changements dans les données
watch([allProduits, montantMaximal, montantMinimal], () => {
  if (allProduits.value.length > 0) {
    createChart();
  }
});

onMounted(() => {
  fetchTotalProduits();
});
</script>

<style scoped>
/* Style scoped pour le composant */
h3 {
  text-align: center;
  margin-bottom: 15px;
  color: #333;
}

canvas {
  width: 100% !important;
  height: calc(100% - 50px) !important;
}
</style>

<style>
.parent {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: 120px auto;
  gap: 20px;
  background-color: #F1F5F9;
  margin-top: 22px;
}

.title{
  font-size: 20px;
  font-weight: bold;
  color: #333333;
  text-align: center;
}

.value{
  font-size: 30px;
  font-weight: bold;
  color:rgb(55, 198, 255);
  text-align: center;
}

.div5 {
  height: 595px;
  background-color: white;
  grid-column: span 4 / span 4;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s;
  padding: 15px;
  display: flex;
  flex-direction: column;
  position: relative;
}

.div1,
.div2,
.div3,
.div4{
  background-color: #FFF;
  justify-content: center;
  align-items: center;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.div1:hover,
.div2:hover,
.div3:hover,
.div4:hover{
  transform: translateY(-5px); 
  cursor: pointer; /* Indiquer que l'élément est interactif */
}
</style>