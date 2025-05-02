<template>
  <header>
    <nav>
      <router-link to="/ajouter">Ajouter</router-link>
      <router-link to="/lister-modifier">Lister & Modifier</router-link>
      <router-link to="/bilan">Bilan & Graphe</router-link>
    </nav>
  </header>

  <main>
    <router-view></router-view>
  </main>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>

<style scoped>
nav {
  padding: 1rem 0;
  border-bottom: 1px solid #ccc;
}

nav a {
  margin: 0 1rem;
  text-decoration: none;
  color: #333;
}

nav a.router-link-exact-active {
  color: #007bff;
  font-weight: bold;
}
</style>