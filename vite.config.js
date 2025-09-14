import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // "true" es más simple y efectivo para refrescar vistas de Blade y componentes de Livewire
            refresh: true,
        }),
        // Vite tomará la configuración automáticamente de tu archivo tailwind.config.js
        tailwindcss(),
    ],
});