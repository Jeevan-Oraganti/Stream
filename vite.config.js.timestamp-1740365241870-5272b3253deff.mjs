// vite.config.js
import { defineConfig } from "file:///home/bnetworks/websites/stream/node_modules/vite/dist/node/index.js";
import laravel from "file:///home/bnetworks/websites/stream/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///home/bnetworks/websites/stream/node_modules/@vitejs/plugin-vue2/dist/index.mjs";
import { nodePolyfills } from "file:///home/bnetworks/websites/stream/node_modules/vite-plugin-node-polyfills/dist/index.js";
import tailwindcss from "file:///home/bnetworks/websites/stream/node_modules/@tailwindcss/vite/dist/index.mjs";
var vite_config_default = defineConfig({
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm.js"
    }
  },
  build: {
    sourcemap: true,
    commonjsOptions: {
      /**
       * Setting to make prod-build working with vue-slider-component
       **/
      requireReturnsDefault: true
    }
    // cssCodeSplit: false
  },
  plugins: [
    laravel([
      "resources/js/app.js"
    ]),
    tailwindcss(),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    nodePolyfills()
    // Add the polyfill plugin to your configuration
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
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvaG9tZS9ibmV0d29ya3Mvd2Vic2l0ZXMvc3RyZWFtXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvaG9tZS9ibmV0d29ya3Mvd2Vic2l0ZXMvc3RyZWFtL3ZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9ob21lL2JuZXR3b3Jrcy93ZWJzaXRlcy9zdHJlYW0vdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tIFwidml0ZVwiO1xuaW1wb3J0IGxhcmF2ZWwgZnJvbSBcImxhcmF2ZWwtdml0ZS1wbHVnaW5cIjtcbmltcG9ydCB2dWUgZnJvbSBcIkB2aXRlanMvcGx1Z2luLXZ1ZTJcIjtcbmltcG9ydCB7IG5vZGVQb2x5ZmlsbHMgfSBmcm9tIFwidml0ZS1wbHVnaW4tbm9kZS1wb2x5ZmlsbHNcIjtcbmltcG9ydCB0YWlsd2luZGNzcyBmcm9tICdAdGFpbHdpbmRjc3Mvdml0ZSc7XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gICAgcmVzb2x2ZToge1xuICAgICAgICBhbGlhczoge1xuICAgICAgICAgICAgdnVlOiBcInZ1ZS9kaXN0L3Z1ZS5lc20uanNcIixcbiAgICAgICAgfSxcbiAgICB9LFxuICAgIGJ1aWxkOiB7XG4gICAgICAgIHNvdXJjZW1hcDogdHJ1ZSxcbiAgICAgICAgY29tbW9uanNPcHRpb25zOiB7XG4gICAgICAgICAgICAvKipcbiAgICAgICAgICAgICAqIFNldHRpbmcgdG8gbWFrZSBwcm9kLWJ1aWxkIHdvcmtpbmcgd2l0aCB2dWUtc2xpZGVyLWNvbXBvbmVudFxuICAgICAgICAgICAgICoqL1xuICAgICAgICAgICAgcmVxdWlyZVJldHVybnNEZWZhdWx0OiB0cnVlLFxuICAgICAgICB9LFxuICAgICAgICAvLyBjc3NDb2RlU3BsaXQ6IGZhbHNlXG4gICAgfSxcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIGxhcmF2ZWwoW1xuICAgICAgICAgICAgXCJyZXNvdXJjZXMvanMvYXBwLmpzXCJcbiAgICAgICAgXSksXG4gICAgICAgIHRhaWx3aW5kY3NzKCksXG4gICAgICAgIHZ1ZSh7XG4gICAgICAgICAgICB0ZW1wbGF0ZToge1xuICAgICAgICAgICAgICAgIHRyYW5zZm9ybUFzc2V0VXJsczoge1xuICAgICAgICAgICAgICAgICAgICBiYXNlOiBudWxsLFxuICAgICAgICAgICAgICAgICAgICBpbmNsdWRlQWJzb2x1dGU6IGZhbHNlLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICB9LFxuICAgICAgICB9KSxcbiAgICAgICAgbm9kZVBvbHlmaWxscygpLCAvLyBBZGQgdGhlIHBvbHlmaWxsIHBsdWdpbiB0byB5b3VyIGNvbmZpZ3VyYXRpb25cbiAgICBdLFxuICAgIGNzczoge1xuICAgICAgICBwcmVwcm9jZXNzb3JPcHRpb25zOiB7XG4gICAgICAgICAgICBzY3NzOiB7XG4gICAgICAgICAgICAgICAgLy8gSWYgeW91IG5lZWQgZ2xvYmFsIFNDU1MgdmFyaWFibGVzLCBtaXhpbnMsIGV0Yy5cbiAgICAgICAgICAgICAgICBhZGRpdGlvbmFsRGF0YTogYEBpbXBvcnQgXCJyZXNvdXJjZXMvc2Fzcy9fdmFyaWFibGVzLnNjc3NcIjtgXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgLy8gb3IgZm9yIC5zYXNzIGlmIHlvdSdyZSB1c2luZyBTQVNTIHN5bnRheDpcbiAgICAgICAgICAgIHNhc3M6IHtcbiAgICAgICAgICAgICAgICBhZGRpdGlvbmFsRGF0YTogYEBpbXBvcnQgXCJyZXNvdXJjZXMvc2Fzcy9fdmFyaWFibGVzLnNhc3NcImBcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG59KTtcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBK1EsU0FBUyxvQkFBb0I7QUFDNVMsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sU0FBUztBQUNoQixTQUFTLHFCQUFxQjtBQUM5QixPQUFPLGlCQUFpQjtBQUV4QixJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixTQUFTO0FBQUEsSUFDTCxPQUFPO0FBQUEsTUFDSCxLQUFLO0FBQUEsSUFDVDtBQUFBLEVBQ0o7QUFBQSxFQUNBLE9BQU87QUFBQSxJQUNILFdBQVc7QUFBQSxJQUNYLGlCQUFpQjtBQUFBO0FBQUE7QUFBQTtBQUFBLE1BSWIsdUJBQXVCO0FBQUEsSUFDM0I7QUFBQTtBQUFBLEVBRUo7QUFBQSxFQUNBLFNBQVM7QUFBQSxJQUNMLFFBQVE7QUFBQSxNQUNKO0FBQUEsSUFDSixDQUFDO0FBQUEsSUFDRCxZQUFZO0FBQUEsSUFDWixJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxJQUNELGNBQWM7QUFBQTtBQUFBLEVBQ2xCO0FBQUEsRUFDQSxLQUFLO0FBQUEsSUFDRCxxQkFBcUI7QUFBQSxNQUNqQixNQUFNO0FBQUE7QUFBQSxRQUVGLGdCQUFnQjtBQUFBLE1BQ3BCO0FBQUE7QUFBQSxNQUVBLE1BQU07QUFBQSxRQUNGLGdCQUFnQjtBQUFBLE1BQ3BCO0FBQUEsSUFDSjtBQUFBLEVBQ0o7QUFDSixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
