import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue2";
import { nodePolyfills } from "vite-plugin-node-polyfills";
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm.js",
        },
    },
    build: {
        sourcemap: true,
        commonjsOptions: {
            /**
             * Setting to make prod-build working with vue-slider-component
             **/
            requireReturnsDefault: true,
        },
        // cssCodeSplit: false
    },
    plugins: [
        laravel([
            "resources/js/app.js",
            "resources/css/app.css",
            "public/css/app.css",
        ]),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        nodePolyfills(), // Add the polyfill plugin to your configuration
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // If you need global SCSS variables, mixins, etc.
                additionalData: `@import "resources/sass/_variables.scss";`
            },
            // or for .sass if you're using SASS syntax:
            sass: {
                additionalData: `@import "resources/sass/_variables.sass"`
            }
        }
    },
});
