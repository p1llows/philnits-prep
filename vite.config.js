import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'node:fs';

export default defineConfig({
    plugins: [
        {
            name: 'clean-hot-on-build',
            buildStart() {
                try {
                    fs.unlinkSync('public/hot');
                } catch (e) {}
            },
        },
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),
    ],
});

