<template>
  <div class="container">
    <h1>Bilan des Produits</h1>
    
    <div class="parent">
      <div class="div1">
        <div class="title">Total des Produits</div>
        <div class="value">{{ totalProduits }}</div>
      </div>

      <div class="div2">
        <div class="title">Total des Montants</div>
        <div class="value">{{ totalMontant.toFixed(2) }} €</div>
      </div>

      <div class="div3">
        <div class="title">Montant maximum</div>
        <div class="value">{{ montantMaximal.toFixed(2) }} €</div>
      </div>

      <div class="div4">
        <div class="title">Montant minimum</div>
        <div class="value">{{ montantMinimal.toFixed(2) }} €</div>
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
        
        <div v-else-if="allProduits.length === 0" class="no-data">
          Aucune donnée disponible pour le graphique
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
        
        <div v-else class="chart-container">
          <canvas ref="chartCanvas" id="produitsChart"></canvas>
        </div>
        
        <button @click="showDebug = !showDebug" class="btn-debug">
          {{ showDebug ? 'Masquer' : 'Afficher' }} Debug
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'

// Variables pour le graphique
let produitsChart = null
let Chart = null

// Variables de données
const totalProduits = ref(0)
const totalMontant = ref(0)
const montantMaximal = ref(0)
const montantMinimal = ref(0)
const allProduits = ref([])
const isLoading = ref(true)
const chartJsAvailable = ref(false)
const canvasFound = ref(false)
const errorMessage = ref('')
const showDebug = ref(false)
const chartCanvas = ref(null)

// Fonction pour obtenir la couleur de la barre
const getBarColor = (montant) => {
  if (montant === montantMinimal.value) {
    return '#ffbb33' // Jaune pour le min
  } else if (montant === montantMaximal.value) {
    return '#ff6384' // Rouge pour le max
  } else {
    return '#4bc0c0' // Couleur standard
  }
}

// Fonction pour charger Chart.js
const loadChartJs = () => {
  return new Promise((resolve, reject) => {
    if (typeof window !== 'undefined' && window.Chart) {
      Chart = window.Chart
      chartJsAvailable.value = true
      resolve()
      return
    }

    const script = document.createElement('script')
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js'
    script.onload = () => {
      Chart = window.Chart
      chartJsAvailable.value = true
      console.log('Chart.js chargé avec succès')
      resolve()
    }
    script.onerror = () => {
      console.error('Erreur lors du chargement de Chart.js')
      chartJsAvailable.value = false
      reject(new Error('Impossible de charger Chart.js'))
    }
    document.head.appendChild(script)
  })
}

// Fonction pour récupérer les données
const fetchTotalProduits = async () => {
  try {
    isLoading.value = true
    console.log('Récupération des données...')
    
    // Utiliser votre API réelle
    const response = await fetch('http://localhost/backend/bilan_produits.php')
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    const data = await response.json()
    
    // Commentez ou supprimez la partie des données simulées
    /*
    const simulatedData = {
      totalProduits: 6,
      totalMontant: 150451.00,
      montantMaximal: 78300.00,
      montantMinimal: 669.00,
      produits: [
        // ... données simulées
      ]
    }
    const data = simulatedData
    */
    
    if (data && typeof data === 'object') {
      totalProduits.value = data.totalProduits || 0
      totalMontant.value = data.totalMontant || 0
      montantMaximal.value = data.montantMaximal || 0
      montantMinimal.value = data.montantMinimal || 0
      allProduits.value = data.produits || []
      
      console.log('Produits récupérés:', allProduits.value)
    } else {
      console.error('Réponse API inattendue:', data)
      errorMessage.value = 'Réponse API invalide'
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des produits:', error)
    errorMessage.value = error.message
  } finally {
    isLoading.value = false
  }
}

// Fonction pour créer le graphique
const createChart = async () => {
  if (!chartJsAvailable.value || !Chart || !chartCanvas.value) {
    console.log('Conditions non remplies pour créer le graphique')
    return
  }
  
  console.log('Création du graphique avec', allProduits.value.length, 'produits')
  
  // Destruction du graphique existant
  if (produitsChart) {
    produitsChart.destroy()
    produitsChart = null
  }

  // Vérifier qu'on a des données
  if (!allProduits.value || allProduits.value.length === 0) {
    console.error('Aucune donnée de produit disponible')
    return
  }

  canvasFound.value = true

  // Préparer les données avec gestion des noms longs
  const labels = allProduits.value.map(produit => {
    const designation = produit.design || `Produit ${produit.numproduit}`
    // Raccourcir les noms trop longs pour l'affichage sur l'axe X
    return designation.length > 25 ? designation.substring(0, 25) + '...' : designation
  })
  const montants = allProduits.value.map(produit => parseFloat(produit.montant) || 0)
  
  const backgroundColors = montants.map(montant => {
    if (montant === montantMinimal.value) {
      return 'rgba(255, 187, 51, 0.8)'
    } else if (montant === montantMaximal.value) {
      return 'rgba(255, 99, 132, 0.8)'
    } else {
      return 'rgba(75, 192, 192, 0.6)'
    }
  })

  const borderColors = backgroundColors.map(color => color.replace('0.8', '1').replace('0.6', '0.8'))

  try {
    produitsChart = new Chart(chartCanvas.value, {
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
        layout: {
          padding: {
            bottom: 50 // Ajouter de l'espace en bas pour les labels
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Montant (€)',
              font: {
                size: 14,
                weight: 'bold'
              }
            },
            ticks: {
              callback: function(value) {
                return value.toLocaleString('fr-FR') + ' €'
              }
            }
          },
          x: {
            title: {
              display: true,
              text: 'Produits',
              font: {
                size: 14,
                weight: 'bold'
              }
            },
            ticks: {
              autoSkip: false,
              maxRotation: 45,
              minRotation: 45,
              font: {
                size: 11
              },
              // Améliorer l'affichage des labels longs
              callback: function(value, index) {
                const label = this.getLabelForValue(value)
                // Diviser les labels longs en plusieurs lignes
                if (label.length > 20) {
                  const words = label.split(' ')
                  if (words.length > 1) {
                    const mid = Math.ceil(words.length / 2)
                    return [words.slice(0, mid).join(' '), words.slice(mid).join(' ')]
                  }
                }
                return label
              }
            }
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              font: {
                size: 14
              }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: {
              size: 14,
              weight: 'bold'
            },
            bodyFont: {
              size: 12
            },
            callbacks: {
              title: function(context) {
                const produit = allProduits.value[context[0].dataIndex]
                return produit ? produit.design : 'Produit'
              },
              label: function(context) {
                let label = context.dataset.label || ''
                if (label) {
                  label += ': '
                }
                if (context.parsed.y !== null) {
                  label += context.parsed.y.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  }) + ' €'
                }
                return label
              },
              afterLabel: function(context) {
                const produit = allProduits.value[context.dataIndex]
                if (produit) {
                  return [
                    `Prix unitaire: ${produit.prix.toLocaleString('fr-FR')}€`,
                    `Quantité: ${produit.quantite.toLocaleString('fr-FR')}`
                  ]
                }
                return ''
              }
            }
          }
        }
      }
    })
    
    console.log('Graphique créé avec succès')
  } catch (error) {
    console.error('Erreur lors de la création du graphique:', error)
    errorMessage.value = 'Erreur création graphique: ' + error.message
  }
}

// Watcher pour recréer le graphique quand les données changent
watch([allProduits, chartJsAvailable], async () => {
  if (allProduits.value.length > 0 && chartJsAvailable.value && !isLoading.value) {
    await nextTick()
    createChart()
  }
})

onMounted(async () => {
  console.log('Composant monté')
  try {
    await loadChartJs()
    await fetchTotalProduits()
    
    if (chartJsAvailable.value && allProduits.value.length > 0) {
      await nextTick()
      createChart()
    }
  } catch (error) {
    console.error('Erreur lors de l\'initialisation:', error)
    errorMessage.value = error.message
  }
})
</script>

<style scoped>
.container {
  padding: 20px;
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

h3 {
  text-align: center;
  margin-bottom: 15px;
  color: #333;
}

.parent {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: 120px auto;
  gap: 20px;
  background-color: #F1F5F9;
  margin-top: 22px;
}

.title {
  font-size: 20px;
  font-weight: bold;
  color: #333333;
  text-align: center;
  padding: 10px;
}

.value {
  font-size: 30px;
  font-weight: bold;
  color: rgb(55, 198, 255);
  text-align: center;
  padding: 10px;
}

.div1, .div2, .div3, .div4 {
  background-color: #FFF;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  margin-top: 25px;
}

.div1:hover, .div2:hover, .div3:hover, .div4:hover {
  transform: translateY(-5px);
  cursor: pointer;
}

.div5 {
  height: 650px; /* Augmenter la hauteur totale */
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

.chart-container {
  flex: 1;
  position: relative;
  height: 450px; /* Augmenter la hauteur pour plus d'espace */
  margin-top: 20px;
  padding-bottom: 20px; /* Ajouter du padding en bas */
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