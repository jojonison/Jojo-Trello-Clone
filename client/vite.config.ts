import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import path, { dirname } from 'path';
import { fileURLToPath } from 'url';
import ui from '@nuxt/ui/vite'

const __dirname = dirname(fileURLToPath(import.meta.url));
export default defineConfig({
    plugins: [vue(), tailwindcss(), ui()],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./src"),
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5173
    }
})
