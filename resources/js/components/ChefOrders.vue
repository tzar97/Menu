<template>
  <div class="relative min-h-screen bg-cover bg-repeat-y" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <div class="container mx-auto p-7">
      <h1 class="text-3xl font-bold mb-6 text-center">Platos Pendientes</h1>
      <div v-if="orders.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="order in orders" :key="order.id" class="border p-6 rounded-lg shadow-lg bg-white relative text-center">
          <h2 class="text-2xl font-semibold mb-4">Mesa #{{ order.table_id || 'Sin número' }}</h2>

          <button @click="deleteOrder(order.id)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">🗑️</button>

          <div v-if="order.order_items && order.order_items.length">
            <p class="font-bold mb-2">Platos:</p>
            <div v-for="item in order.order_items" :key="item.id" class="border-t border-gray-200 py-2">
              <p class="font-semibold">{{ item.product.name }}</p>
              <p>Cantidad: {{ item.quantity }}</p>
              <p>Precio: ${{ item.price }}</p>
            </div>
          </div>
          <div v-else>
            <p class="text-gray-500">No hay platos en este pedido.</p>
          </div>

          <div class="mt-4 flex justify-center gap-4">
            <button v-if="order.status === 'pendiente'" @click="startOrder(order)" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Iniciar</button>
            <button v-else-if="order.status === 'en preparación'" @click="completeOrder(order)" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Completar</button>
            <div v-else>
              <span class="text-green-700 font-semibold">Finalizado</span>
              <p class="text-gray-600">{{ formatTime(order.preparationTime) }}</p>
            </div>
          </div>
        </div>
      </div>
      <p v-else class="text-center text-gray-500">No hay platos pendientes.</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import backgroundImage from '@/images/fondomenu.jpg';

export default {
  data() {
    return {
      backgroundImage,
      orders: [],
      timers: {} // Para almacenar los temporizadores de cada orden
    };
  },
  created() {
    this.fetchPendingOrders();
  },
  methods: {
    async fetchPendingOrders() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/orders/pending');
        this.orders = response.data.map(order => ({
          ...order,
          status: order.status || 'pendiente',
          order_items: order.order_items.map(item => ({
            ...item,
            price: item.price ? parseFloat(item.price).toFixed(2) : '0.00'
          })),
          preparationTime: 0,
          preparationStartTime: null,
          completed: false // Nuevo campo para marcar como completado
        }));
      } catch (error) {
        console.error('Error al obtener platos pendientes:', error);
      }
    },

    async startOrder(order) {
      try {
        order.status = 'en preparación';
        order.preparationStartTime = new Date();
        this.timers[order.id] = setInterval(() => {
          const elapsed = Math.floor((new Date() - order.preparationStartTime) / 1000);
          order.preparationTime = elapsed;
        }, 1000);
        await axios.post(`http://127.0.0.1:8000/orders/${order.id}/start`);
      } catch (error) {
        console.error('Error al iniciar el pedido:', error);
      }
    },

      async completeOrder(order) {
        try {
      clearInterval(this.timers[order.id]);
      order.status = 'finalizado';
      const preparationTime = order.preparationTime;
        
      await axios.post(`http://127.0.0.1:8000/orders/${order.id}/complete`, { preparationTime });
        
      // Cambia el estado para reflejar que está completado y muestra el tiempo final
      order.completed = true;
    } catch (error) {
      console.error('Error al completar el pedido:', error);
    }
      },

    async deleteOrder(orderId) {
      try {
        await axios.delete(`http://127.0.0.1:8000/orders/${orderId}`);
        this.orders = this.orders.filter(order => order.id !== orderId);
      } catch (error) {
        console.error('Error al eliminar el pedido:', error);
      }
    },

    formatTime(seconds) {
      const minutes = Math.floor(seconds / 60);
      const remainingSeconds = seconds % 60;
      return `${minutes}m ${remainingSeconds}s`;
    }
  }
};
</script>

<style scoped>
.bg-white {
  background-color: white;
}
</style>
