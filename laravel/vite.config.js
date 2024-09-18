import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
          output: {
            manualChunks(id) {
              if (id.includes('node_modules')) {
                return 'vendor'; // Разделение зависимостей на отдельный чанк
              }
            }
          }
        },
        chunkSizeWarningLimit: 1000 // Увеличение лимита до 1000 кБ
      }
});
