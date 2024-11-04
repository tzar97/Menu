import './bootstrap';
import { createApp } from 'vue';
import App from './components/App.vue'; // Componente raíz
import router from './router'; // Router configurado
import store from './store'; // Vuex store configurado (opcional)

// Crea la aplicación Vue
const app = createApp(App);

// Usa el router y store
app.use(router);
app.use(store);

// Monta la app en el div con id 'app'
app.mount('#app');

