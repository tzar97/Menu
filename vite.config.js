import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';  // Importa el plugin de Vue
import path from 'path';  // Importa el módulo path para usar path.resolve

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),  // Agrega el plugin de Vue aquí
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),  // Configuración del alias
        },
    },
});
