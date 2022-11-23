// vite.config.js
import { defineConfig } from "vite";
import Vue from "@vitejs/plugin-vue";
import symfonyPlugin from "vite-plugin-symfony";

export default defineConfig({
    plugins: [Vue(), symfonyPlugin()],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/app.js",
                theme: "./assets/styles/app.css",
            },
        },
    },
});
