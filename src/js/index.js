import { gsap } from 'gsap';

// En-tête (logo, menu) : présent sur toutes les pages et jamais remplacé, initialisé une seule fois
const layout = [
    { selector: '.header__toggle', load: () => import('./custom/menu').then((m) => m.initializeMenu) },
    { selector: '.nav__list', load: () => import('./custom/nav').then((m) => m.initializeNav) },
    { selector: '.nav__cta', load: () => import('./custom/cta').then((m) => m.initializeCta) },
];

// Contenu de la page : composants chargés uniquement si leur sélecteur est présent, réinitialisés à chaque page
// (navigation.js) dans un contexte GSAP annulé en la quittant
const page = [
    { selector: '[data-countdown]', load: () => import('./custom/countdown').then((m) => m.initializeCountdown) },
    { selector: '[data-copy]', load: () => import('./custom/share').then((m) => m.initializeShare) },
    { selector: '.hero', load: () => import('./custom/hero').then((m) => m.initializeHero) },
    { selector: 'main', load: () => import('./custom/reveal').then((m) => m.initializeReveal) },
    { selector: '[data-newsletter]', load: () => import('./custom/newsletter').then((m) => m.initializeNewsletter) },
    { selector: '[data-parallax]', load: () => import('./custom/parallax').then((m) => m.initializeParallax) },
    { selector: '[data-video]', load: () => import('./custom/video').then((m) => m.initializeVideo) },
    { selector: '[data-faq]', load: () => import('./custom/faq').then((m) => m.initializeFaq) },
    { selector: '[data-photo]', load: () => import('./custom/photo').then((m) => m.initializePhoto) },
    { selector: '[data-rgive-src]', load: () => import('./custom/rgive-embed').then((m) => m.initializeRgiveEmbed) },
    { selector: '[data-rgive-goal]', load: () => import('./custom/rgive-goal').then((m) => m.initializeRgiveGoal) },
];

const present = ({ selector }) => document.querySelector(selector);

let context;
// Promesse résolue une fois tous les composants de la page initialisés (navigation.js attend ce moment pour
// découvrir la page)
const enter = () => {
    context = gsap.context(() => {});
    return Promise.all(page.filter(present).map(({ load }) => load().then((initialize) => context.add(() => initialize()))));
};
const leave = () => context?.revert();

document.addEventListener('DOMContentLoaded', () => {
    layout.filter(present).forEach(({ load }) => load().then((initialize) => initialize()));
    enter();
    if (document.querySelector('#content')) {
        import('./custom/navigation').then((m) => m.initializeNavigation({ leave, enter }));
    }
});
