import tailwindcss from '@tailwindcss/vite';
import kirby from 'vite-plugin-kirby';

export default ({ mode }) => ({
    // Sources dans src/, fichiers compilés dans dist/ (servis par Kirby en production)
    root: 'src',
    base: mode === 'development' ? '/' : '/dist/',
    publicDir: false,

    server: {
        // Les pages viennent de Valet (projetnk.test), les assets du serveur Vite
        cors: { origin: 'http://projetnk.test' },
        // Polices et icônes appelées en /assets/… depuis la feuille de style, servie ici en dev
        proxy: { '/assets': 'http://projetnk.test' },
        // Fichiers écrits par Kirby à chaque visite (cache des pages, sessions) : sans rechargement
        watch: { ignored: ['**/site/cache/**', '**/site/sessions/**', '**/site/logs/**'] },
    },

    build: {
        outDir: '../dist',
        emptyOutDir: true,
        assetsDir: 'assets',
        manifest: true,
        rollupOptions: {
            input: ['index.js', 'index.css'],
            output: {
                // Cœur de GSAP dans son propre fichier : les scripts de page l'importent de là et non du fichier principal,
                // dont le nom (empreinte du contenu) change à chaque modification ; ils ne changent ainsi de nom que si leur
                // propre code change, et un déploiement ne renvoie que les fichiers réellement modifiés
                manualChunks: (id) => (/node_modules\/gsap\/(index|gsap-core|CSSPlugin)\.js$/.test(id) ? 'gsap' : undefined),
            },
        },
    },

    plugins: [
        tailwindcss(),
        kirby({
            watch: ['../site/(templates|snippets|controllers|models|layouts)/**/*.php', '../content/**/*'],
        }),
    ],
});
