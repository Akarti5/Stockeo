import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Importez le router
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';
import Swal from 'sweetalert2';



import './assets/main.css';

const app = createApp(App);

app.use(router); // Utilisez le router
app.mount('#app');


