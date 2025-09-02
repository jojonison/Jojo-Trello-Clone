// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2025-07-15',
    devtools: {enabled: true},
    modules: [
        '@nuxt/eslint',
        '@nuxt/image',
        '@nuxt/ui',
        '@pinia/nuxt',
    ],
    runtimeConfig: {
        public: {apiBase: '127.0.0.1:8000/api'},
    },
    components: [{path: '~/components'}],
    ssr: false,
    css: ['~/assets/css/tailwind.css'],
    postcss: {
        plugins: {
            '@tailwindcss/postcss': {}
        }
    }
})