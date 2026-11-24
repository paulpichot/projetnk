import { gsap } from 'gsap';

// Menu mobile : ouverture / fermeture du panneau de navigation. À l'ouverture, le panneau apparaît en fondu et
// ses liens montent l'un après l'autre ; à la fermeture, il s'efface en fondu
export function initializeMenu() {
    const toggle = document.querySelector('.header__toggle');
    const panel = document.querySelector('.nav');
    const items = panel.querySelectorAll('.nav__list > a, .nav > a, .nav > ul');
    const root = document.documentElement;
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
    let animation;

    const close = () => {
        root.classList.remove('is-menu-open');
        gsap.set([panel, ...items], { clearProps: 'opacity,visibility,transform' });
    };

    const setOpen = (open) => {
        const isOpen = root.classList.contains('is-menu-open');
        toggle.setAttribute('aria-expanded', open);
        toggle.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        animation?.kill();

        if (open) {
            root.classList.add('is-menu-open');
            if (reduce.matches) return;
            animation = gsap.timeline()
                .fromTo(panel, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.3, ease: 'power1.out' })
                .fromTo(items, { autoAlpha: 0, y: 16 }, { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power2.out', stagger: 0.05 }, 0.1);
        } else if (isOpen && !reduce.matches) {
            animation = gsap.to(panel, { autoAlpha: 0, duration: 0.25, ease: 'power1.in', onComplete: close });
        } else {
            close();
        }
    };

    toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
    document.addEventListener('keydown', (e) => e.key === 'Escape' && setOpen(false));
    // Lien suivi : le panneau se referme pendant le changement de page (navigation.js)
    document.addEventListener('navigation:start', () => setOpen(false));
}
