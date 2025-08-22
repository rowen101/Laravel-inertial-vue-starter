import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { vuestic } from '@vuestic/compiler/vite';
import { resolve, dirname } from 'node:path';
export default defineConfig({
    plugins: [
         vuestic({
        devtools: true,
        cssLayers: true,
        }),
        vue(),
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
    alias: {
      '@': resolve(__dirname, './resources'), // Add this line
    },
  },
});
