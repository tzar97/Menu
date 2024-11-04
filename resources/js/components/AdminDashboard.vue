<template>
  <div class="relative min-h-screen bg-cover bg-repeat-y" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <div class="container mx-auto p-7">
      <h1 class="text-3xl font-bold mb-6 text-center">Ventas Realizadas</h1>
      <div v-if="sales.length" class="grid grid-cols-1 gap-6">
        <!-- Listado de ventas -->

        
        <div v-for="sale in sales" :key="sale.id" class="border p-4 rounded-lg shadow-lg bg-white text-center">
          <p><strong>Pedido ID:</strong> {{ sale.order_id }}</p>
          <p>Nombre del Plato: {{ sale.product ? sale.product.name : 'Producto no disponible' }}</p>
          <p><strong>Cantidad:</strong> {{ sale.quantity }}</p>
          <p><strong>Precio:</strong> ${{ sale.price }}</p>
          <p><strong>Tiempo de preparación:</strong> {{ sale.preparation_time }} segundos</p>
          <p><strong>Fecha de venta:</strong> {{ sale.sale_date }}</p>
        </div>
      </div>
      <p v-else class="text-center text-gray-500">No hay ventas registradas.</p>
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
      sales: []
    };
  },
  created() {
    this.fetchSalesData();
  },
  methods: {
    async fetchSalesData() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/sales');
        console.log(response.data);
        this.sales = response.data;
      } catch (error) {
        console.error('Error al obtener los datos de ventas:', error);
      }
    }
  }
};
</script>

<style scoped>
.bg-white {
  background-color: white;
}
</style>
