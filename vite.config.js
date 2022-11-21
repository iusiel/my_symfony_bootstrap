// vite.config.js
import { defineConfig } from "vite";
import Vue from "@vitejs/plugin-vue";
import symfonyPlugin from "vite-plugin-symfony";

export default defineConfig({
    plugins: [Vue(), symfonyPlugin()],
    // ...
    test: {
        // enable jest-like global test APIs
        globals: true,
        // simulate DOM with happy-dom
        // (requires installing happy-dom as a peer dependency)
        environment: "happy-dom",
    },
    build: {
        rollupOptions: {
            input: {
                app: "./assets/app.js",
                theme: "./assets/styles/app.scss",
            },
        },
    },
});
