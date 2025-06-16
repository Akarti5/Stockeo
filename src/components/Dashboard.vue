<template>
    <div class="dashboard-container">
      <div class="dashboard-header">
        <h1>Dashboard</h1>
        <div class="date-filter">
          <i class="fas fa-calendar-alt"></i>
          <span>Last 30 days</span>
          <i class="fas fa-chevron-down"></i>
        </div>
      </div>
  
      <div v-if="isLoading" class="loading-indicator">Chargement des données...</div>
      <div v-else class="dashboard-grid">
        
        <div class="card summary-card">
          <div class="card-header">
            <h3>Résumé de l'inventaire</h3>
          </div>
          <div class="card-content">
            <div class="summary-grid">
              <div class="stat-item">
                <span class="stat-value">{{ totalProduits }}</span>
                <span class="stat-label">Produits Uniques</span>
              </div>
              <div class="stat-item">
                <span class="stat-value">{{ totalQuantite }}</span>
                <span class="stat-label">Unités en stock</span>
              </div>
              <div class="stat-item">
                <span class="stat-value">{{ formatCurrency(totalMontant) }}</span>
                <span class="stat-label">Valeur totale</span>
              </div>
              <div class="stat-item">
                 <span class="stat-value">{{ formatCurrency(montantMaximal) }}</span>
                <span class="stat-label">Actif le + cher</span>
              </div>
            </div>
          </div>
        </div>
  
        <div class="card chart-card">
          <div class="card-header">
            <h3>Top 5 Produits par Valeur</h3>
          </div>
          <div class="card-content">
            <div class="chart-container">
              <canvas ref="produitsChartCanvas"></canvas>
            </div>
          </div>
        </div>
  
        <div class="list-card card">
          <div class="card-header">
            <h3>Échantillon de produits</h3>
            <a href="#" class="view-all">Voir tout</a>
          </div>
          <div class="card-content">
            <table>
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Désignation</th>
                  <th>Quantité</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="produit in produitsRecents" :key="produit.numproduit">
                  <td><img src="https://via.placeholder.com/40" alt="img" class="product-image"/></td>
                  <td>{{ produit.design }}</td>
                  <td>{{ produit.quantite }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <div class="card key-stats-card">
          <div class="card-header">
            <h3>Statistiques Clés</h3>
          </div>
          <div class="card-content">
             <div class="key-stat-item">
               <i class="fas fa-arrow-up stat-icon-up"></i>
               <div class="stat-details">
                  <span class="stat-label">Produit le + populaire (Qté)</span>
                  <span class="stat-value-small">{{ produitPlusPopulaire?.design || 'N/A' }}</span>
               </div>
             </div>
             <div class="key-stat-item">
               <i class="fas fa-arrow-down stat-icon-down"></i>
               <div class="stat-details">
                  <span class="stat-label">Produit le - populaire (Qté)</span>
                  <span class="stat-value-small">{{ produitMoinsPopulaire?.design || 'N/A' }}</span>
               </div>
             </div>
          </div>
        </div>
  
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed, nextTick, watch } from 'vue';
  
  let Chart = null;
  let produitsChartInstance = null;
  
  // Refs pour les éléments du DOM
  const produitsChartCanvas = ref(null);
  
  // État du composant
  const isLoading = ref(true);
  const allProduits = ref([]);
  const totalProduits = ref(0);
  const totalMontant = ref(0);
  const montantMaximal = ref(0);
  
  // --- PROPRIÉTÉS COMPUTED (calculs dérivés) ---
  
  // Calcule la quantité totale de tous les produits
  const totalQuantite = computed(() => {
    return allProduits.value.reduce((sum, p) => sum + (parseInt(p.quantite, 10) || 0), 0);
  });
  
  // Extrait un échantillon des 5 premiers produits pour la liste
  const produitsRecents = computed(() => {
    return allProduits.value.slice(0, 4);
  });
  
  // Trouve le produit avec la plus grande quantité
  const produitPlusPopulaire = computed(() => {
    if (allProduits.value.length === 0) return null;
    return [...allProduits.value].sort((a, b) => b.quantite - a.quantite)[0];
  });
  
  // Trouve le produit avec la plus petite quantité
  const produitMoinsPopulaire = computed(() => {
    if (allProduits.value.length === 0) return null;
    return [...allProduits.value].sort((a, b) => a.quantite - b.quantite)[0];
  });
  
  
  // --- FONCTIONS ---
  
  // Formate un nombre en devise
  const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value || 0);
  };
  
  // Charge la librairie Chart.js depuis un CDN
  const loadChartJs = () => {
    return new Promise((resolve, reject) => {
      if (window.Chart) {
        Chart = window.Chart;
        resolve();
        return;
      }
      const script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js';
      script.onload = () => {
        Chart = window.Chart;
        resolve();
      };
      script.onerror = reject;
      document.head.appendChild(script);
    });
  };
  
  // Récupère les données depuis le backend
  const fetchData = async () => {
    try {
      isLoading.value = true;
      const response = await fetch('http://localhost/backend/bilan_produits.php');
      if (!response.ok) throw new Error("Erreur de réseau");
      
      const data = await response.json();
      allProduits.value = data.produits || [];
      totalProduits.value = data.totalProduits || 0;
      totalMontant.value = data.totalMontant || 0;
      montantMaximal.value = data.montantMaximal || 0;
  
    } catch (error) {
      console.error("Erreur lors de la récupération des données:", error);
      // Gérer l'erreur, par ex. afficher un message à l'utilisateur
    } finally {
      isLoading.value = false;
    }
  };
  
  // Crée le graphique
  const createChart = () => {
    if (!Chart || !produitsChartCanvas.value || allProduits.value.length === 0) return;
  
    if (produitsChartInstance) {
      produitsChartInstance.destroy();
    }
    
    // Trier les produits par montant et prendre le top 5
    const topProduits = [...allProduits.value]
      .sort((a, b) => b.montant - a.montant)
      .slice(0, 5);
  
    const ctx = produitsChartCanvas.value.getContext('2d');
    produitsChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: topProduits.map(p => p.design),
        datasets: [{
          label: 'Valeur en Stock (€)',
          data: topProduits.map(p => p.montant),
          backgroundColor: 'rgba(59, 130, 246, 0.5)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 1,
          borderRadius: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (context) => `${context.dataset.label}: ${formatCurrency(context.raw)}`
            }
          }
        },
        scales: {
          y: { 
            beginAtZero: true,
            ticks: { callback: (value) => `${value / 1000}k €` }
          },
          x: { grid: { display: false } }
        }
      }
    });
  };
  
  // Cycle de vie du composant
  onMounted(async () => {
    await fetchData();
    try {
      await loadChartJs();
      await nextTick();
      createChart();
    } catch (error) {
      console.error("Impossible de charger le script du graphique.", error);
    }
  });
  
  // Surveiller les changements de données pour recréer le graphique
  watch(allProduits, async () => {
    await nextTick();
    createChart();
  });
  </script>
  
  <style scoped>
  /* Polices et variables de couleur */
  :root {
    --bg-color: #F8FAFC;
    --card-bg-color: #FFFFFF;
    --text-primary: #1E293B;
    --text-secondary: #64748B;
    --border-color: #E2E8F0;
    --primary-color: #3B82F6;
    --green-color: #10B981;
    --red-color: #EF4444;
  }
  
  .dashboard-container {
    padding: 24px;
    background-color: var(--bg-color);
    font-family: 'Inter', sans-serif;
    color: var(--text-primary);
  }
  
  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }
  
  .dashboard-header h1 {
    font-size: 28px;
    font-weight: 700;
  }
  
  .date-filter {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    color: var(--text-secondary);
  }
  
  .loading-indicator {
    text-align: center;
    padding: 50px;
    font-size: 18px;
    color: var(--text-secondary);
  }
  
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: auto auto;
    gap: 24px;
  }
  
  /* Style des cartes */
  .card {
    background-color: var(--card-bg-color);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
  }
  
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .card-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
  }
  
  .view-all {
    font-size: 14px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
  }
  
  /* Carte de résumé */
  .summary-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
  
  .stat-item {
    display: flex;
    flex-direction: column;
  }
  
  .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
  }
  .stat-value-small {
    font-size: 16px;
    font-weight: 600;
  }
  
  .stat-label {
    font-size: 14px;
    color: var(--text-secondary);
    margin-top: 4px;
  }
  
  /* Carte de graphique */
  .chart-container {
    height: 250px;
  }
  
  /* Carte de liste */
  .list-card table {
    width: 100%;
    border-collapse: collapse;
  }
  .list-card th, .list-card td {
    text-align: left;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
  }
  .list-card thead th {
    color: var(--text-secondary);
    font-weight: 500;
  }
  .list-card tbody tr:last-child td {
    border-bottom: none;
  }
  .product-image {
    width: 40px;
    height: 40px;
    border-radius: 8px;
  }
  
  /* Carte de statistiques clés */
  .key-stats-card .card-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .key-stat-item {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .stat-icon-up, .stat-icon-down {
    font-size: 20px;
    padding: 8px;
    border-radius: 50%;
    color: white;
  }
  .stat-icon-up { background-color: var(--green-color); }
  .stat-icon-down { background-color: var(--red-color); }
  
  .stat-details {
    display: flex;
    flex-direction: column;
  }
  
  </style>