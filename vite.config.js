import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import compression from 'vite-plugin-compression';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Styles principaux (architecture modulaire)
                'resources/sass/guest.scss',
                'resources/sass/app.scss',
                
                // JavaScript
                'resources/js/app.js',
                // 'resources/js/accessibility.js', // DÉSACTIVÉ - Interfère avec les modals Bootstrap
            ],
            refresh: true,
        }),
        // Compression Gzip pour les fichiers de production
        compression({
            algorithm: 'gzip',
            ext: '.gz',
            threshold: 10240, // Compresser seulement les fichiers > 10kb
            deleteOriginFile: false
        }),
        // Compression Brotli (meilleure compression)
        compression({
            algorithm: 'brotliCompress',
            ext: '.br',
            threshold: 10240,
            deleteOriginFile: false
        }),
        // Visualisation du bundle (généré uniquement en build)
        visualizer({
            filename: 'storage/logs/bundle-stats.html',
            open: false,
            gzipSize: true,
            brotliSize: true,
        })
    ],
    resolve: {
        alias: {
            '@': '/resources',
            '$': 'jquery'
        },
    },
    optimizeDeps: {
        exclude: ['js-big-decimal']
    },
    build: {
        // Minification optimale
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Supprimer les console.log en production
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info', 'console.debug', 'console.warn']
            },
            format: {
                comments: false // Supprimer les commentaires
            }
        },

        // Code splitting optimisé
        rollupOptions: {
            output: {
                manualChunks: {
                    // Vendor chunks - Bibliothèques tierces
                    'vendor-core': ['jquery', 'axios', 'alpinejs'],
                    'vendor-ui': ['bootstrap', 'sweetalert2', 'toastr'],
                    'vendor-charts': ['chart.js'],
                    'vendor-utils': ['moment', 'select2', 'jquery-mask-plugin']
                },
                // Nommage des chunks pour le cache
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/\.(png|jpe?g|svg|gif|tiff|bmp|ico)$/i.test(assetInfo.name)) {
                        return `images/[name]-[hash].${ext}`;
                    }
                    if (/\.(woff2?|eot|ttf|otf)$/i.test(assetInfo.name)) {
                        return `fonts/[name]-[hash].${ext}`;
                    }
                    return `assets/[name]-[hash].${ext}`;
                }
            }
        },

        // Optimisation des chunks
        chunkSizeWarningLimit: 1000,

        // Source maps pour debug (désactiver en production si non nécessaire)
        sourcemap: false,

        // Compression et optimisation
        cssCodeSplit: true,

        // Target moderne pour des bundles plus petits
        target: 'es2015',

        // Optimisation de l'arbre de dépendances
        reportCompressedSize: true
    },

    // Performance
    server: {
        hmr: {
            overlay: true
        }
    }
});
