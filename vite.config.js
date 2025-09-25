import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        server: {
            host: '0.0.0.0',
            port: env.VITE_DEV_PORT || 5173,
            hmr: {
                host: env.VITE_DEV_HOST || 'localhost',
            },
        },
        build: {
            outDir: `public/${env.VITE_BUILD_DIR || 'build'}`,
        },
        resolve: {
            alias: {
                vue: 'vue/dist/vue.esm-bundler.js',
                '@': '/resources/js',
            },
        },
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
                buildDirectory: env.VITE_BUILD_DIR || 'build',
            }),
            vue(),
        ],
    };
});
