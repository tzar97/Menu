<template>
  <div class="relative min-h-screen bg-cover bg-repeat-y" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <div class="container mx-auto p-7">
      <h1 class="text-3xl font-bold mb-6 text-center">Ventas Realizadas</h1>

      <!-- Filtros -->
      <div class="flex flex-wrap gap-4 mb-6 justify-center">
        <div>
          <label for="startDate">Fecha de inicio:</label>
          <input type="date" id="startDate" v-model="filters.startDate" @change="applyFilters" class="border p-1 rounded">
        </div>
        <div>
          <label for="endDate">Fecha de fin:</label>
          <input type="date" id="endDate" v-model="filters.endDate" @change="applyFilters" class="border p-1 rounded">
        </div>
        <div>
          <label for="product">Producto:</label>
          <select id="product" v-model="filters.productId" @change="applyFilters" class="border p-1 rounded">
            <option value="">Todos</option>
            <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
          </select>
        </div>
        <div>
          <label for="prepTime">Tiempo de preparación:</label>
          <select id="prepTime" v-model="filters.preparationTime" @change="applyFilters" class="border p-1 rounded">
            <option value="">Todos</option>
            <option value="less5">Menos de 5 min</option>
            <option value="5to10">5 - 10 min</option>
            <option value="more10">Más de 10 min</option>
          </select>
        </div>
      </div>

      <!-- Tabla de ventas -->
      <div v-if="filteredSales.length" class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border p-2 text-left">Pedido</th>
              <th class="border p-2 text-left">Nombre del Plato</th>
              <th class="border p-2 text-left">Cantidad</th>
              <th class="border p-2 text-left">Precio</th>
              <th class="border p-2 text-left">Tiempo de Preparación</th>
              <th class="border p-2 text-left">Fecha de Venta</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="sale in filteredSales" :key="sale.id" class="text-center">
              <td class="border p-2">{{ sale.order_id }}</td>
              <td class="border p-2">{{ sale.product ? sale.product.name : 'Producto no disponible' }}</td>
              <td class="border p-2">{{ sale.quantity }}</td>
              <td class="border p-2">${{ sale.price }}</td>
              <td class="border p-2">{{ sale.preparation_time }} segundos</td>
              <td class="border p-2">{{ sale.sale_date }}</td>
            </tr>
          </tbody>
        </table>
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
      sales: [],
      products: [],
      filters: {
        startDate: '',
        endDate: '',
        productId: '',
        preparationTime: '',
      },
    };
  },
  computed: {
    filteredSales() {
      return this.sales.filter(sale => {
        const saleDate = new Date(sale.sale_date);
        const startDate = this.filters.startDate ? new Date(this.filters.startDate) : null;
        const endDate = this.filters.endDate ? new Date(this.filters.endDate) : null;

        let meetsDateCriteria = true;
        if (startDate && saleDate < startDate) meetsDateCriteria = false;
        if (endDate && saleDate > endDate) meetsDateCriteria = false;

        let meetsProductCriteria = true;
        if (this.filters.productId && sale.product_id !== this.filters.productId) meetsProductCriteria = false;

        let meetsPrepTimeCriteria = true;
        if (this.filters.preparationTime === 'less5' && sale.preparation_time >= 300) meetsPrepTimeCriteria = false;
        if (this.filters.preparationTime === '5to10' && (sale.preparation_time < 300 || sale.preparation_time > 600)) meetsPrepTimeCriteria = false;
        if (this.filters.preparationTime === 'more10' && sale.preparation_time <= 600) meetsPrepTimeCriteria = false;

        return meetsDateCriteria && meetsProductCriteria && meetsPrepTimeCriteria;
      });
    },
  },
  created() {
    this.fetchSalesData();
    this.fetchProducts();
  },
  methods: {
    async fetchSalesData() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/sales');
        this.sales = response.data;
      } catch (error) {
        console.error('Error al obtener los datos de ventas:', error);
      }
    },
    async fetchProducts() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/products');
        this.products = response.data;
      } catch (error) {
        console.error('Error al obtener los datos de productos:', error);
      }
    },
    applyFilters() {
      // Actualiza los filtross.
    }
  }
};
</script>

<style scoped>
.bg-white {
  background-color: white;
}
</style>
