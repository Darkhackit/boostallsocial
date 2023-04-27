import path from 'path'
import { defineConfig } from 'vite';
import Vue from "@vitejs/plugin-vue";
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        Vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/main.js'],
            refresh: true,
        }),
    ],
    resolve: {
        extensions: [".mjs", ".js", ".ts", ".jsx", ".tsx", ".json", ".vue", ".css"],
        alias: {
            "@": path.resolve(__dirname, "./resources/js/"),
        },
    },

});
