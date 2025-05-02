<template>
    <h1>Bilan des Produits</h1>
  
    <div v-if="bilan">
      <p>Montant Minimal: {{ bilan.montantMinimal ? bilan.montantMinimal.toFixed(2) : 'N/A' }}</p>
      <p>Montant Maximal: {{ bilan.montantMaximal ? bilan.montantMaximal.toFixed(2) : 'N/A' }}</p>
      <p>Montant Total: {{ bilan.totalMontant ? bilan.totalMontant.toFixed(2) : 'N/A' }}</p>
  
      <div style="width:400px; margin: 20px auto;">
        <canvas id="bilan-chart"></canvas>
      </div>
    </div>
    <p v-else>Chargement du bilan...</p>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { Chart, BarController, PieController, CategoryScale, LinearScale, Title, Tooltip, Legend } from 'chart.js';
  
  Chart.register(BarController, PieController, CategoryScale, LinearScale, Title, Tooltip, Legend);
  
  const bilan = ref(null);
  const chartInstance = ref(null);
  
  const fetchBilan = async () => {
    try {
      const response = await fetch('http://localhost/backend/bilan_produits.php');
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      bilan.value = data;
      updateChart();
    } catch (error) {
      console.error('Erreur lors de la récupération du bilan:', error);
    }
  };
  
  const updateChart = () => {
    if (bilan.value) {
      const ctx = document.getElementById('bilan-chart');
  
      if (chartInstance.value) {
        chartInstance.value.destroy(); // Détruire l'ancienne instance si elle existe
      }
  
      chartInstance.value = new Chart(ctx, {
        type: 'bar', // Vous pouvez changer 'bar' en 'pie' pour un camembert
        data: {
          labels: ['Minimal', 'Maximal', 'Total'],
          datasets: [{
            label: 'Montant (MAD)', // Adaptez la devise si nécessaire
            data: [bilan.value.montantMinimal, bilan.value.montantMaximal, bilan.value.totalMontant],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
            ],
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            title: {
              display: true,
              text: 'Bilan des Montants des Produits'
            },
            legend: {
              display: false
            }
          }
        }
      });
    }
  };
  
  onMounted(fetchBilan);
  </script>