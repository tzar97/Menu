import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';  // El nuevo componente Home
import Menu from './components/Menu.vue';
import ChefOrders from './components/ChefOrders.vue';
import AdminDashboard from './components/AdminDashboard.vue';

const routes = [
  { path: '/', component: Home },    // Ruta principal
  { path: '/menu', component: Menu },  // Componente Menú
  { path: '/chef', component: ChefOrders },  // Componente Chef
  { path: '/admin', component: AdminDashboard },  // Panel de Administración
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
