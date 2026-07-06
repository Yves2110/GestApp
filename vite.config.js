import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/university-theme.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor': ['bootstrap', '@popperjs/core'],
                    'charts': ['chart.js'],
                    'utils': ['lodash', 'axios'],
                    'jquery': ['jquery'],
                    'datatables': ['datatables.net-bs5']
                }
            }
        },
        chunkSizeWarningLimit: 1000,
        assetsInlineLimit: 4096,
    },
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: false,
        open: false,
    },
    define: {
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
    }
});
