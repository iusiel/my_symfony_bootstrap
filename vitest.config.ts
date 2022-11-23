import { configDefaults, defineConfig } from "vitest/config";
import Vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [Vue()],
    test: {
        include: ["**/*.{test,spec}.{js,mjs,cjs,ts,mts,cts,jsx,tsx}"],
        globals: true,
        environment: "jsdom",
    },
});
