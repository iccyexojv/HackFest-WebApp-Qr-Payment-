import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
                'resources/css/filament/participant/theme.css',
                'resources/css/filament/stall/theme.css',
                'resources/css/filament/visitor/theme.css',
                'resources/css/filament/wallet/theme.css',
                'resources/css/filament/custom-resources-and-pages/theme.css',
            ],
            refresh: true,
        }),
    ],
});
