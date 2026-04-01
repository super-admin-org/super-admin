import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  css: {
    preprocessorOptions: {
      scss: {
        silenceDeprecations: ['import', 'global-builtin'],
      },
    },
  },
  build: {
    outDir: 'resources/assets/tailwind-light/dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        'super-admin': resolve(__dirname, 'resources/assets/tailwind-light/css/app.css'),
        'super-admin-icons': resolve(__dirname, 'resources/assets/tailwind-light/css/icons.scss'),
        'super-admin-js': resolve(__dirname, 'resources/assets/tailwind-light/js/app.js'),
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name].[ext]',
      },
    },
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/assets'),
    },
  },
});
