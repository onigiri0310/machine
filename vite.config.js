import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
    port: 8000, // Laravelのデフォルトのポート番号
  },
  plugins: [
    laravel({
      input: {
        app: '/resources/js/app.js',
        product: '/resources/js/product.js',
      },
      refresh: true,
    }),
  ],
});
