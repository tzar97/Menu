<template>
  <div class="relative h-screen w-screen">
    <!-- Fondo de la imagen -->
    <div class="absolute inset-0">
      <img :src="backgroundImage" class="h-full w-full object-cover" alt="Fondo difuminado" />
    </div>

    <!-- Contenido con posicionamiento absoluto -->
    <div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 translate-y-10 flex flex-col items-center backdrop-blur-md p-8 rounded-lg max-w-xs">      
      <h1 class="text-4xl font-bold mb-4">Bienvenido</h1>
      <p class="text-lg mb-8">Elige una sección:</p>
      <div class="space-y-4 w-full">
        <button @click="goToMenu" class="w-full bg-blue-300 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded">
          Menú
        </button>
        <button @click="goToChef" class="w-full bg-blue-300 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded">
          Pedidos del Chef
        </button>
        <button @click="goToAdmin" class="w-full bg-blue-300 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded">
          Administración
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import backgroundImage from '@/images/fondo.jpg';  // Asegúrate de mover la imagen a "resources/images"

export default {
  data() {
    return {
      backgroundImage
    };
  },
  methods: {
    goToMenu() {
      this.$router.push('/menu');
    },
    goToChef() {
      this.$router.push('/chef');
    },
    goToAdmin() {
      this.$router.push('/admin');
    }
  }
}
</script>
