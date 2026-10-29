// Composants chargés uniquement si leur sélecteur est présent dans la page
const components = [
    { selector: '.header__toggle', load: () => import('./custom/menu').then((m) => m.initializeMenu) },
    { selector: '[data-cookies]', load: () => import('./custom/cookies').then((m) => m.initializeCookies) },
    { selector: '[data-countdown]', load: () => import('./custom/countdown').then((m) => m.initializeCountdown) },
    { selector: '[data-copy]', load: () => import('./custom/share').then((m) => m.initializeShare) },
    { selector: '[data-video]', load: () => import('./custom/video').then((m) => m.initializeVideo) },
];

document.addEventListener('DOMContentLoaded', () => {
    components
        .filter(({ selector }) => document.querySelector(selector))
        .forEach(({ load }) => load().then((initialize) => initialize()));
});
