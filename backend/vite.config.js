import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 12000,
        strictPort: true,
        cors: {
            origin: '*',
            methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With'],
        },
        hmr: {
            host: 'work-1-nftqwglffzohutke.prod-runtime.all-hands.dev',
        },
        headers: {
            'X-Frame-Options': 'ALLOWALL',
            'Access-Control-Allow-Origin': '*',
            'Content-Security-Policy': "frame-ancestors 'self' *",
        },
    },
});
