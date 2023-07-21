import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue2 from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/addon.js'],
            publicDirectory: 'dist',
            hotFile: 'vite.hot',
        }),
        vue2(),
    ],
});
