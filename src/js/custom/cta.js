import { gsap } from 'gsap';

// Bouton « Faire un don » du menu : effacé sur la page de don (classe is-support du <body>, head.php, mise à jour à
// chaque changement de page par navigation.js). Desktop : fondu et repli de sa largeur, le menu glissant à sa place ;
// mobile (bouton dans le panneau du menu) et mouvement réduit : masqué sans animation.
// Avant ce script, la feuille de style le masque déjà sur la page de don (classe sans data-animated, nav.php)
export function initializeCta() {
    const cta = document.querySelector('.nav__cta');
    if (!cta) return;
    const animate = window.matchMedia('(min-width: 1024px) and (prefers-reduced-motion: no-preference)');
    const isHidden = () => document.body.classList.contains('is-support');
    const collapsed = { autoAlpha: 0, width: 0, paddingLeft: 0, paddingRight: 0, borderLeftWidth: 0, borderRightWidth: 0 };
    let hidden = isHidden();

    // Dimensions naturelles du bouton (relevées sans styles posés par ce script)
    const natural = () => {
        gsap.set(cta, { clearProps: 'all' });
        cta.hidden = false;
        const style = getComputedStyle(cta);
        return {
            width: cta.getBoundingClientRect().width,
            paddingLeft: style.paddingLeft,
            paddingRight: style.paddingRight,
            borderLeftWidth: style.borderLeftWidth,
            borderRightWidth: style.borderRightWidth,
        };
    };

    const apply = (withAnimation) => {
        if (!animate.matches || !withAnimation) {
            gsap.killTweensOf(cta);
            gsap.set(cta, { clearProps: 'all' });
            cta.hidden = hidden;
            return;
        }
        // Transition CSS des marges et du fond (survol) suspendue pendant l'animation
        if (hidden) {
            // Une fois replié, masqué par l'attribut hidden plutôt que par ses styles (le menu mobile efface l'opacité et
            // la visibilité de ses liens à chaque changement de page, menu.js)
            gsap.to(cta, {
                ...collapsed,
                transition: 'none',
                overflow: 'hidden',
                duration: 0.5,
                ease: 'power2.inOut',
                overwrite: true,
                onComplete: () => {
                    gsap.set(cta, { clearProps: 'all' });
                    cta.hidden = true;
                },
            });
        } else {
            // Taille naturelle relevée puis repli posé dans la même image (aucun clignotement), avant de déplier
            const size = natural();
            gsap.fromTo(cta, { ...collapsed, overflow: 'hidden', transition: 'none' }, {
                ...size,
                autoAlpha: 1,
                duration: 0.5,
                ease: 'power2.inOut',
                overwrite: true,
                onComplete: () => gsap.set(cta, { clearProps: 'all' }),
            });
        }
    };

    cta.dataset.animated = '';
    apply(false);
    new MutationObserver(() => {
        if (isHidden() === hidden) return;
        hidden = isHidden();
        apply(true);
    }).observe(document.body, { attributes: true, attributeFilter: ['class'] });
    animate.addEventListener('change', () => apply(false));
}
