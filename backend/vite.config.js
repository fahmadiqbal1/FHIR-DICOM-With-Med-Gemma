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
            methods: ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
            allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With', 'X-CSRF-TOKEN', 'Accept'],
            exposedHeaders: ['Content-Disposition'],
            credentials: true,
            preflightContinue: false,
            optionsSuccessStatus: 204
        },
        hmr: {
            host: 'work-1-nftqwglffzohutke.prod-runtime.all-hands.dev',
            protocol: 'https'
        },
        headers: {
            'X-Frame-Options': 'ALLOWALL',
            'Access-Control-Allow-Origin': '*',
            'Content-Security-Policy': "frame-ancestors 'self' *; default-src 'self' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https: wss:;",
            'X-Content-Type-Options': 'nosniff',
            'Referrer-Policy': 'strict-origin-when-cross-origin'
        },
        watch: {
            usePolling: true
        }
    },
});
