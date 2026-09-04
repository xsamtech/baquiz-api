import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import react from './public/template/node_modules/@vitejs/plugin-react/dist/index.mjs';

export default defineConfig({
    resolve: {
        alias: [
            { find: 'react/jsx-runtime', replacement: resolve(__dirname, 'public/template/node_modules/react/jsx-runtime.js') },
            { find: 'react-dom/client', replacement: resolve(__dirname, 'public/template/node_modules/react-dom/client.js') },
            { find: 'react-dom', replacement: resolve(__dirname, 'public/template/node_modules/react-dom/index.js') },
            { find: 'react', replacement: resolve(__dirname, 'public/template/node_modules/react/index.js') },
        ],
    },
    plugins: [
        react(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.jsx'],
            refresh: true,
        }),
    ],
});
