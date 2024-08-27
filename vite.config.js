import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js2/app.js"],
            refresh: true,
            detectTls: "road-trip-planner.fly.dev",
        }),
    ],
});
