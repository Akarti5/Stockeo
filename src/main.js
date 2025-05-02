import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Importez le router

import './assets/main.css';

const app = createApp(App);

app.use(router); // Utilisez le router
app.mount('#app');