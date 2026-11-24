import Swup from 'swup';
import SwupHeadPlugin from '@swup/head-plugin';
import SwupPreloadPlugin from '@swup/preload-plugin';
import { gsap } from 'gsap';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin';

gsap.registerPlugin(ScrollToPlugin);

// Navigation entre les pages du site sans rechargement (Swup) : seul le contenu (<main>) est remplacé, l'en-tête
// (logo, menu) reste affiché. Un voile de la couleur de fond, sous l'en-tête et par-dessus tout le contenu, recouvre
// la page en passant à la couleur de la page suivante ; le contenu est remplacé derrière lui, puis il se retire.
// Toute la surface change ainsi de couleur d'un seul tenant, quelles que soient les sections des deux pages.
// Défilement : page remontée en haut (ou, au retour arrière, rendue là où on l'avait quittée) derrière le voile ;
// celui-ci ne passe sous le menu que si le menu reste à l'écran d'une page à l'autre, sinon il le couvre aussi.
// leave : avant le remplacement du contenu (annulation des animations de la page quittée) ;
// enter : nouveau contenu en place, page remontée en haut (animations de la nouvelle page)
export function initializeNavigation({ leave, enter }) {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
    const content = () => document.querySelectorAll('main, footer');
    const root = document.documentElement;
    const header = document.querySelector('.header');
    const veil = document.createElement('div');
    veil.className = 'pointer-events-none invisible fixed inset-0';
    document.body.append(veil);
    const positions = new Map();
    let incoming;
    let target = 0;
    let ready = Promise.resolve();
    const nextFrame = () => new Promise((resolve) => requestAnimationFrame(resolve));

    // Couleur de fond d'une page d'après les classes de son <body> (plugin background, head.php)
    const backgroundOf = (className) => {
        const probe = document.createElement('div');
        probe.className = className;
        document.body.append(probe);
        const color = getComputedStyle(probe).backgroundColor;
        probe.remove();
        return color;
    };

    const swup = new Swup({
        containers: ['#content'],
        animationSelector: false,
        // Retour arrière et avant animés comme les autres changements de page (position de défilement rendue)
        animateHistoryBrowsing: true,
        // Clic déjà pris en charge (écran d'intro), panel et fichiers (médias, PDF…) : navigation classique
        ignoreVisit: (url, { el, event } = {}) => Boolean(
            event?.defaultPrevented
            || el?.closest('[data-no-swup]')
            || url.startsWith('/panel')
            || /\.\w+$/.test(url.split(/[?#]/)[0]),
        ),
        plugins: [
            new SwupHeadPlugin({ persistAssets: true }),
            new SwupPreloadPlugin(),
        ],
    });

    // Position de défilement de chaque page quittée, et défilement doux du site (scroll-smooth) suspendu pendant le
    // changement de page : les positions sont réglées directement
    swup.hooks.on('visit:start', (visit) => {
        positions.set(visit.from.url, window.scrollY);
        target = visit.history.popstate ? positions.get(visit.to.url) ?? 0 : 0;
        root.style.scrollBehavior = 'auto';
        document.dispatchEvent(new Event('navigation:start'));
    });
    swup.hooks.on('visit:end', () => root.style.removeProperty('scroll-behavior'));

    // Départ : le contenu descend à peine pendant que le voile le recouvre, dans la couleur actuelle de la page.
    // Menu à l'écran avant et après : voile dessous, la page remontant au besoin en douceur jusqu'à la position d'arrivée
    swup.hooks.replace('animation:out:await', async () => {
        if (reduce.matches) return;
        // Départ de l'écran d'arrivée (intro.js) : il a déjà pris la couleur de la page suivante, le voile le relaie
        // tel quel, sans second fondu
        const screen = root.classList.contains('is-intro') && document.querySelector('.intro-screen');
        if (screen) {
            gsap.set(veil, { zIndex: 1, autoAlpha: 1, backgroundColor: getComputedStyle(screen).backgroundColor });
            return;
        }
        const menuStays = window.scrollY < header.offsetHeight && target < header.offsetHeight;
        gsap.set(veil, { zIndex: menuStays ? 1 : 3, backgroundColor: getComputedStyle(document.body).backgroundColor });
        await Promise.all([
            gsap.to(content(), { y: 8, duration: 0.45, ease: 'power1.inOut' }),
            gsap.to(veil, { autoAlpha: 1, duration: 0.45, ease: 'power1.inOut' }),
            menuStays && window.scrollY !== target && gsap.to(window, { scrollTo: target, duration: 0.45, ease: 'power1.inOut' }),
        ]);
    });

    // Page suivante chargée (pendant le départ) : le voile prend sa couleur de fond ; son lien du menu (aria-current),
    // où glisse le fond noir (nav.js)
    swup.hooks.on('page:load', (visit, { page }) => {
        incoming = new DOMParser().parseFromString(page.html, 'text/html');
        gsap.to(veil, { backgroundColor: backgroundOf(incoming.body.className), duration: 0.45, ease: 'power1.inOut' });
        const links = [...incoming.querySelectorAll('.nav a')];
        document.querySelectorAll('.nav a').forEach((link, index) => {
            const current = links[index]?.getAttribute('aria-current');
            if (current) link.setAttribute('aria-current', current);
            else link.removeAttribute('aria-current');
        });
        document.dispatchEvent(new Event('navigation:change'));
    });

    // Contenu remplacé derrière le voile : fond de la nouvelle page (classes du <body>) et pied de page en place
    swup.hooks.before('content:replace', leave);
    swup.hooks.on('content:replace', () => {
        document.body.className = incoming.body.className;
        gsap.set(content(), { clearProps: 'transform' });
    });
    swup.hooks.replace('scroll:top', () => {
        window.scrollTo(0, target);
        return true;
    });
    swup.hooks.on('content:scroll', () => {
        ready = Promise.resolve(enter());
    });

    // Arrivée : le voile se retire sur la nouvelle page, dont les éléments apparaissent (reveal.js). Il attend que
    // la page soit prête (découpage des textes…) et deux images de plus : lancé pendant ce travail, son fondu
    // sauterait d'un coup ses premières étapes
    swup.hooks.replace('animation:in:await', async () => {
        if (reduce.matches) return;
        await ready;
        await nextFrame();
        await nextFrame();
        await gsap.to(veil, { autoAlpha: 0, duration: 0.5, ease: 'power1.out' });
    });
}
