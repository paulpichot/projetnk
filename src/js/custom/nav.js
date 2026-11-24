import { gsap } from 'gsap';

// Menu principal : le fond noir du lien actif glisse jusqu'au lien survolé, et jusqu'au lien de la nouvelle page
// à chaque changement de page (navigation.js)
export function initializeNav() {
    const list = document.querySelector('.nav__list');
    const links = [...list.querySelectorAll('.nav__link')];
    const findCurrent = () => links.find((link) => link.getAttribute('aria-current') === 'page');
    let current = findCurrent();
    // Enregistré avant le fond noir (matchMedia ci-dessous), qui glisse ensuite vers ce lien
    document.addEventListener('navigation:change', () => (current = findCurrent()));
    // Lien cliqué retenu dès le clic : si le menu quitte le curseur avant l'arrivée de la page (écran d'intro qui
    // remonte), le fond noir reste sur lui au lieu de repartir vers l'ancien lien actif
    links.forEach((link) => link.addEventListener('click', (event) => {
        if (event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey) current = link;
    }));

    gsap.matchMedia().add({
        desktop: '(hover: hover) and (min-width: 1024px)',
        reduce: '(prefers-reduced-motion: reduce)',
    }, ({ conditions }) => {
        if (!conditions.desktop) return;

        const duration = conditions.reduce ? 0 : 0.35;
        const indicator = document.createElement('span');
        indicator.className = 'absolute inset-y-0 left-0 bg-encre';
        list.prepend(indicator);

        const highlight = (target, animate = true, slide = duration) => {
            links.forEach((link) => link.toggleAttribute('data-highlighted', link === target));
            if (!target) return gsap.to(indicator, { autoAlpha: 0, duration: slide });

            // Mesures au sous-pixel près : offsetLeft/offsetWidth arrondis laissent un filet clair contre la bordure.
            // Origine lue sur le fond lui-même (position sans son décalage) : clientLeft, entier, arrondit la bordure
            // de 1,5px et décalait le fond d'un demi-pixel au-delà du contour, côté « Nous soutenir »
            const origin = indicator.getBoundingClientRect().left - gsap.getProperty(indicator, 'x');
            const rect = target.getBoundingClientRect();
            gsap[animate ? 'to' : 'set'](indicator, {
                x: rect.left - origin,
                width: rect.width,
                autoRound: false,
                autoAlpha: 1,
                duration: slide,
                ease: 'power3.out',
            });
        };

        const reset = () => highlight(current, false);
        const change = () => highlight(current, true, 0.6);
        // Fond noir et couleur du texte repris tels que le CSS les affichait (data-highlighted posé avant
        // data-indicator) : sans quoi le texte du lien actif repasse brièvement au noir
        reset();
        list.dataset.indicator = '';
        document.fonts.ready.then(reset);
        window.addEventListener('resize', reset);
        document.addEventListener('navigation:change', change);
        links.forEach((link) => link.addEventListener('mouseenter', () => highlight(link)));
        list.addEventListener('mouseleave', () => highlight(current));

        return () => {
            window.removeEventListener('resize', reset);
            document.removeEventListener('navigation:change', change);
            indicator.remove();
            delete list.dataset.indicator;
            links.forEach((link) => link.removeAttribute('data-highlighted'));
        };
    });
}
