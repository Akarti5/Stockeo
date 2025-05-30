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
        {{ totalMontant.toFixed(2) }} &euro;
      </div>
    </div>

    <div class="div3">
      <div class="title">
        Montant maximum
      </div>
      <div class="value">
        {{ montantMaximal.toFixed(2) }} &euro;
      </div>
    </div>

    <div class="div4">
      <div class="title">
        Montant minimum 
      </div>
      <div class="value">
        {{ montantMinimal.toFixed(2) }} &euro;
      </div>
    </div>

    <div class="div5">
      <h3>Statistiques des produits</h3>
      
      <!-- Debug info -->
      <div v-if="showDebug" class="debug-info">
        <p><strong>Debug:</strong></p>
        <p>Chart.js disponible: {{ chartJsAvailable ? 'Oui' : 'Non' }}</p>
        <p>Nombre de produits: {{ allProduits.length }}</p>
        <p>Canvas trouvé: {{ canvasFound ? 'Oui' : 'Non' }}</p>
        <p>Erreur: {{ errorMessage || 'Aucune' }}</p>
        <button @click="showDebug = false" class="btn-close-debug">Fermer Debug</button>
      </div>
      
      <div v-if="isLoading" class="loading">
        Chargement du graphique...
      </div>
      
      <div v-else-if="!chartJsAvailable" class="error-message">
        <p>Chart.js n'est pas disponible. Affichage alternatif :</p>
        <div class="alternative-chart">
          <div v-for="(produit, index) in allProduits" :key="produit.numproduit" class="bar-item">
            <div class="bar-label">{{ produit.design }}</div>
            <div class="bar-container">
              <div 
                class="bar" 
                :style="{ 
                  width: (produit.montant / montantMaximal * 100) + '%',
                  backgroundColor: getBarColor(produit.montant)
                }"
              ></div>
              <span class="bar-value">{{ produit.montant.toFixed(2) }}€</span>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else-if="allProduits.length === 0" class="no-data">
        Aucune donnée disponible pour le graphique
      </div>
      
      <div v-else class="chart-container">
        <canvas id="produitsChart" width="400" height="300"></canvas>
      </div>
      
      <button @click="showDebug = !showDebug" class="btn-debug">
        {{ showDebug ? 'Masquer' : 'Afficher' }} Debug
      </button>
    </div>
  </div>
</template>
  
<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';

// Variables pour le graphique
let produitsChart = null;
let Chart = null;

// Variables de données
const totalProduits = ref(0);
const totalMontant = ref(0);
const montantMaximal = ref(0);
const montantMinimal = ref(0);
const allProduits = ref([]);
const isLoading = ref(true);
const chartJsAvailable = ref(false);
const canvasFound = ref(false);
const errorMessage = ref('');
const showDebug = ref(false);

// Fonction pour obtenir la couleur de la barre
const getBarColor = (montant) => {
  if (montant === montantMinimal.value) {
    return '#ffbb33'; // Jaune pour le min
  } else if (montant === montantMaximal.value) {
    return '#ff6384'; // Rouge pour le max
  } else {
    return '#4bc0c0'; // Couleur standard
  }
};

// Fonction pour charger Chart.js dynamiquement
const loadChartJs = async () => {
  try {
    // Essayer de charger Chart.js via CDN
    if (typeof window.Chart === 'undefined') {
      const script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
      script.onload = () => {
        Chart = window.Chart;
        chartJsAvailable.value = true;
        console.log('Chart.js chargé via CDN');
        if (allProduits.value.length > 0) {
          createChart();
        }
      };
      script.onerror = () => {
        console.error('Impossible de charger Chart.js via CDN');
        chartJsAvailable.value = false;
      };
      document.head.appendChild(script);
    } else {
      Chart = window.Chart;
      chartJsAvailable.value = true;
      console.log('Chart.js déjà disponible');
    }
  } catch (error) {
    console.error('Erreur lors du chargement de Chart.js:', error);
    errorMessage.value = error.message;
    chartJsAvailable.value = false;
  }
};

// Fonction pour récupérer les valeurs totales
const fetchTotalProduits = async () => {
  try {
    isLoading.value = true;
    console.log('Récupération des données...');
    
    // Utiliser fetch au lieu d'axios pour éviter les dépendances
    const response = await fetch('http://localhost/backend/bilan_produits.php');
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    console.log('Réponse reçue:', data);
    
    if (data && typeof data === 'object') {
      totalProduits.value = data.totalProduits || 0;
      totalMontant.value = data.totalMontant || 0;
      montantMaximal.value = data.montantMaximal || 0;
      montantMinimal.value = data.montantMinimal || 0;
      allProduits.value = data.produits || [];
      
      console.log('Produits récupérés:', allProduits.value);
      
      // Attendre le prochain tick pour s'assurer que le DOM est mis à jour
      await nextTick();
      
      // Création du graphique si Chart.js est disponible
      if (chartJsAvailable.value && allProduits.value.length > 0) {
        createChart();
      }
    } else {
      console.error('Réponse API inattendue:', data);
      errorMessage.value = 'Réponse API invalide';
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des produits:', error);
    errorMessage.value = error.message;
  } finally {
    isLoading.value = false;
  }
};
  
// Fonction pour créer le graphique avec Chart.js
const createChart = () => {
  if (!chartJsAvailable.value || !Chart) {
    console.log('Chart.js non disponible');
    return;
  }
  
  console.log('Création du graphique avec', allProduits.value.length, 'produits');
  
  // Destruction du graphique existant s'il existe
  if (produitsChart) {
    produitsChart.destroy();
    produitsChart = null;
  }

  // Obtenir le contexte du canvas
  const ctx = document.getElementById('produitsChart');
  if (!ctx) {
    console.error('Canvas non trouvé');
    canvasFound.value = false;
    return;
  }
  
  canvasFound.value = true;

  // Vérifier qu'on a des données
  if (!allProduits.value || allProduits.value.length === 0) {
    console.error('Aucune donnée de produit disponible');
    return;
  }

  // Préparer les données pour le graphique
  const labels = allProduits.value.map(produit => produit.design || `Produit ${produit.numproduit}`);
  const montants = allProduits.value.map(produit => parseFloat(produit.montant) || 0);
  
  console.log('Labels:', labels);
  console.log('Montants:', montants);
  
  // Déterminer les couleurs
  const backgroundColors = montants.map(montant => {
    if (montant === montantMinimal.value) {
      return 'rgba(255, 187, 51, 0.8)';
    } else if (montant === montantMaximal.value) {
      return 'rgba(255, 99, 132, 0.8)';
    } else {
      return 'rgba(75, 192, 192, 0.6)';
    }
  });

  const borderColors = backgroundColors.map(color => color.replace('0.8', '1').replace('0.6', '0.8'));

  // Créer le graphique
  try {
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
                  label += context.parsed.y.toFixed(2) + ' €';
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
    
    console.log('Graphique créé avec succès');
  } catch (error) {
    console.error('Erreur lors de la création du graphique:', error);
    errorMessage.value = 'Erreur création graphique: ' + error.message;
  }
};

// Surveiller les changements dans les données
watch([allProduits, chartJsAvailable], () => {
  if (allProduits.value.length > 0 && chartJsAvailable.value && !isLoading.value) {
    console.log('Données mises à jour, recréation du graphique');
    nextTick(() => {
      createChart();
    });
  }
});

onMounted(async () => {
  console.log('Composant monté');
  await loadChartJs();
  await fetchTotalProduits();
});
</script>

<style scoped>
h3 {
  text-align: center;
  margin-bottom: 15px;
  color: #333;
}

.chart-container {
  flex: 1;
  position: relative;
  height: 400px;
}

canvas {
  width: 100% !important;
  height: 100% !important;
}

.loading, .no-data, .error-message {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 300px;
  font-size: 1.2rem;
  color: #666;
  font-style: italic;
}

.loading {
  color: #1a73e8;
}

.no-data {
  color: #ff6b6b;
}

.error-message {
  color: #ff6b6b;
}

.debug-info {
  background-color: #f0f0f0;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 15px;
  font-size: 0.9rem;
}

.btn-debug, .btn-close-debug {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 5px 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.8rem;
}

.btn-close-debug {
  position: static;
  margin-top: 10px;
}

.alternative-chart {
  width: 100%;
  max-height: 400px;
  overflow-y: auto;
}

.bar-item {
  margin-bottom: 15px;
}

.bar-label {
  font-weight: bold;
  margin-bottom: 5px;
  color: #333;
}

.bar-container {
  display: flex;
  align-items: center;
  height: 30px;
  background-color: #f0f0f0;
  border-radius: 4px;
  position: relative;
}

.bar {
  height: 100%;
  border-radius: 4px;
  transition: width 0.3s ease;
  min-width: 2px;
}

.bar-value {
  position: absolute;
  right: 10px;
  font-weight: bold;
  color: #333;
  font-size: 0.9rem;
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
  margin-top: 25px;
}

.div1:hover,
.div2:hover,
.div3:hover,
.div4:hover{
  transform: translateY(-5px); 
  cursor: pointer;
}

h1 {
  text-align: center;
  font-size: 2.2rem;
  color: #1a3c5e;
  margin-bottom: 30px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
}
</style>