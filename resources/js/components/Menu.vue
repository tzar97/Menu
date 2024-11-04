<template>
  <div class="relative min-h-screen bg-cover bg-repeat-y" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <!-- Contenido del menú -->
    <div class="relative container max-w-xxl mx-auto p-7 z-10">
      <h1 class="text-3xl font-bold mb-6">Platos Principales</h1>  
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Productos -->
        <div v-for="product in products" :key="product.id" class="border p-4 rounded-lg shadow-lg">
          <img :src="product.image" alt="Imagen del producto" class="w-full h-48 object-cover mb-4 rounded-lg">
          <h2 class="text-2xl font-semibold mb-2">{{ product.name }}</h2>
          <p class="text-gray-700 mb-4">{{ product.description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-lg font-bold">${{ product.price }}</span>
            <button @click="addToCart(product)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">
              Añadir al carrito
            </button>
          </div>
        </div>
      </div>

      <!-- Sección de Postres -->
      <h1 class="text-3xl font-bold mb-6 mt-10">Postres</h1>  
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div v-for="postre in postres" :key="postre.id" class="border p-4 rounded-lg shadow-lg">
          <img :src="postre.image" alt="Imagen del postre" class="w-full h-48 object-cover mb-4 rounded-lg">
          <h2 class="text-2xl font-semibold mb-2">{{ postre.name }}</h2>
          <p class="text-gray-700 mb-4">{{ postre.description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-lg font-bold">${{ postre.price }}</span>
            <button @click="addToCart(postre)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">
              Añadir al carrito
            </button>
          </div>
        </div>
      </div>

      <!-- Sección del Carrito -->
      <h1 class="text-3xl font-bold mb-6 mt-10">Carrito de Compras</h1>
      <div v-if="cart.length > 0" class="border p-4 rounded-lg shadow-lg mb-10">
        <div v-for="item in cart" :key="item.id" class="mb-4">
          <h2 class="text-2xl font-semibold">{{ item.name }}</h2>
          <p class="text-gray-700">Precio: ${{ item.price }}</p>
        </div>

        <label class="block text-gray-700 text-lg mb-2 text-center" for="tableId">Número de Mesa</label>
        <div class="flex justify-center">
          <input
            type="number"
            v-model="tableId"
            class="border rounded w-32 py-1 px-2 text-gray-700 mb-4"
            placeholder="Mesa"
            required
          />
        </div>


        <button @click="placeOrder" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
          Realizar Pedido
        </button>
      </div>
      <div v-else>
        <p class="text-lg">Tu carrito está vacío.</p>
      </div>
    </div>
  </div>
</template>
  
<script>

  import backgroundImage from '@/images/fondomenu.jpg';
  import axios from 'axios';

  
  export default {
  data() {
    return {
      backgroundImage, // Ruta correcta para la imagen de fondo
      
      products: [
        {
          id: 1,
          name: 'Bistec',
          description: 'Bistec de cerdo con papas fritas.',
          price: 18000,
          image: '/platos/bistec.jpg',
        },
        {
          id: 2,
          name: 'Pasta',
          description: 'Fideos de espinaca al pesto.',
          price: 12000,
          image: 'platos/pasta.jpg',
        },
        {
          id: 3,
          name: 'Pollo',
          description: 'Pollo al horno con papas.',
          price: 15000,
          image: '/platos/pollo.jpg',
        },
        {
          id: 4,
          name: 'Salmon',
          description: 'Salmon a la plancha con arroz y verduras.',
          price: 20000,
          image: '/platos/salmon.jpg',
        },
      ],
      postres: [
        {
          id: 6,
          name: 'Tarta de Chocolate',
          description: 'Tarta de chocolate con helado de vainilla.',
          price: 6000,
          image: '/platos/tarta-chocolate.jpg',
        },
        {
          id: 5,
          name: 'Flan Casero',
          description: 'Flan casero con caramelo.',
          price: 5500,
          image: '/platos/flan.jpg',
        },
      ],
      cart: []
    };
  },
  methods: {
    addToCart(product) {
      this.cart.push(product);
      alert(`Añadido ${product.name} al carrito.`);
    },

       async placeOrder() {
        if (!this.tableId) {
          alert('Por favor, ingresa el número de mesa antes de realizar el pedido.');
          return;
        }
      
        try {
          const cartItems = this.cart.map(item => ({
            product_id: item.id,
            quantity: 1,
            price: item.price,
          }));
        
          const response = await axios.post('http://127.0.0.1:8000/cart/checkout', {
            cart: cartItems,
            table_id: this.tableId // Usando 'table_id' como el número de mesa
          });
        
          alert(response.data.message);
          this.cart = [];
          this.tableId = ''; // Limpia el número de mesa después de realizar el pedido
        } catch (error) {
          console.error('Error al realizar el pedido:', error);
          alert('Hubo un problema al realizar el pedido.');
        }
      }





  }
}
</script>
  
<style scoped>
/* Estilos adicionales si es necesario */
</style>