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
  
    </div>

</div>
    
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  const totalProduits = ref(0);
  const totalMontant = ref(0);
  const montantMaximal = ref(0);
  const montantMinimal = ref(0);
  
  const fetchTotalProduits = async () => {
      try {
          const response = await axios.get('http://localhost/backend/bilan_produits.php');
          if (response.data && response.data !== undefined) {
              totalProduits.value = response.data.totalProduits; // Ensure the backend sends `totalProduits`
              totalMontant.value = response.data.totalMontant; // Ensure the backend sends `totalMontant`
              montantMaximal.value = response.data.montantMaximal; // Ensure the backend sends `montantMaximal`
              montantMinimal.value = response.data.montantMinimal; // Ensure the backend sends `montantMinimal`
          } else {
              console.error('Unexpected API response:', response.data);
          }
      } catch (error) {
          console.error('Erreur lors de la récupération des produits:', error);
      }
  };
  
  onMounted(() => {
      fetchTotalProduits();
  });
  </script>
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
    background-color: purple;
    grid-column: span 4 / span 4;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s;
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