import fs from 'node:fs';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

/**
 * Set VITE_DEV_HTTPS=1 (e.g. in start-dev.bat) to serve the dev server over
 * HTTPS — required for HMR while the site itself is served via the Apache
 * HTTPS vhost (https://photolabe.local), otherwise hot assets are blocked as
 * mixed content. Falls back to plain HTTP automatically when the certificate
 * files are not present.
 */
function devHttpsConfig() {
    if (process.env.VITE_DEV_HTTPS !== '1') {
        return undefined;
    }

    try {
        return {
            key: fs.readFileSync('C:/xampp/apache/conf/ssl.key/photolabe.local.key'),
            cert: fs.readFileSync('C:/xampp/apache/conf/ssl.crt/photolabe.local.crt'),
        };
    } catch {
        console.warn('[vite] VITE_DEV_HTTPS=1 but cert/key not found — falling back to HTTP.');
        return undefined;
    }
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        https: devHttpsConfig(),
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
