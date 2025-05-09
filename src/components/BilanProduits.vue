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
import { ref, onMounted, watch } from 'vue'; // Ajout de l'import de watch
import axios from 'axios';
import Chart from 'chart.js/auto';

// Variables pour le graphique
let produitsChart = null;

// Variables de données
const totalProduits = ref(0);
const totalMontant = ref(0);
const montantMaximal = ref(0);
const montantMinimal = ref(0);

// Fonction pour récupérer les valeurs totales
const fetchTotalProduits = async () => {
    try {
        const response = await axios.get('http://localhost/backend/bilan_produits.php');
        if (response.data && response.data !== undefined) {
            totalProduits.value = response.data.totalProduits;
            totalMontant.value = response.data.totalMontant;
            montantMaximal.value = response.data.montantMaximal;
            montantMinimal.value = response.data.montantMinimal;
            
            // Création du graphique après avoir récupéré les données
            createChart();
        } else {
            console.error('Unexpected API response:', response.data);
        }
    } catch (error) {
        console.error('Erreur lors de la récupération des produits:', error);
    }
};
  
// Fonction pour créer le graphique basé uniquement sur les valeurs min et max
const createChart = () => {
  // Destruction du graphique existant s'il existe
  if (produitsChart) {
    produitsChart.destroy();
  }

  // Obtenir le contexte du canvas
  const ctx = document.getElementById('produitsChart');
  if (!ctx) return;

  // Utiliser les données déjà récupérées
  // On va créer un graphique simple avec deux barres: montant min et max
  const labels = ['Montant Minimal', 'Montant Maximal'];
  const montants = [montantMinimal.value, montantMaximal.value];

  // Créer des couleurs différentes pour les barres
  const backgroundColors = [
    'rgba(54, 162, 235, 0.8)', // Bleu pour le min
    'rgba(255, 99, 132, 0.8)'  // Rouge pour le max
  ];

  // Créer le graphique
  produitsChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Montants des produits',
        data: montants,
        backgroundColor: backgroundColors,
        borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
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
            }
          }
        }
      }
    }
  });
};

// Surveiller les changements dans les données min/max
watch([montantMaximal, montantMinimal], () => {
  if (montantMaximal.value > 0 || montantMinimal.value > 0) {
    createChart(); // Recréer le graphique si les valeurs min/max changent
  }
});

onMounted(() => {
  fetchTotalProduits(); // Chargement des statistiques globales puis création du graphique
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
    background-color: white; /* Changé de purple à white */
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