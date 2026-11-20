import { defineConfig } from 'vite';
import { resolve } from 'path';
import { copyFileSync, existsSync, mkdirSync, readdirSync } from 'fs';

// Copy font files to dist folder after build
function copyFonts() {
  return {
    name: 'copy-fonts',
    writeBundle() {
      const srcDir = resolve(__dirname, 'resources/assets/fontawesome/webfonts');
      const destDir = resolve(__dirname, 'resources/assets/tailwind-light/dist');

      if (!existsSync(destDir)) {
        mkdirSync(destDir, { recursive: true });
      }

      const files = readdirSync(srcDir);
      for (const file of files) {
        if (file.startsWith('fa-')) {
          copyFileSync(resolve(srcDir, file), resolve(destDir, file));
        }
      }
    }
  };
}

export default defineConfig({
  plugins: [copyFonts()],
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
    assetsInlineLimit: 0,
    cssCodeSplit: true,
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
  base: './',
});
