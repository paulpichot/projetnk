import { gsap } from 'gsap';

// FAQ : réponse dépliée et repliée en douceur (hauteur et fondu), flèche qui pivote vers le bas ; une seule question
// ouverte à la fois (en ouvrir une referme les autres).
// Pivot au centre du tracé de la flèche (11px sur 24 : la pointe s'arrête 2px avant le bord), sans quoi elle se décale.
// Sans animation (mouvement réduit), l'accordéon natif <details> reste tel quel, une question ouverte refermant les autres
export function initializeFaq() {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

    document.querySelectorAll('[data-faq]').forEach((faq) => {
        const items = [...faq.querySelectorAll('details')].map((details) => {
            const summary = details.querySelector('summary');
            const answer = summary.nextElementSibling;
            const arrow = summary.querySelector('svg');
            let animation;

            // Calque GPU gardé après la rotation : pas de recalage au demi-pixel en fin d'animation
            gsap.set(arrow, { rotation: details.open ? 90 : 0, transformOrigin: '11px 12px', force3D: true });

            const isOpen = () => details.open && details.dataset.closing === undefined;

            const open = () => {
                // Depuis la hauteur actuelle (réponse en train de se replier) ou depuis zéro
                animation?.kill();
                const from = details.open ? answer.offsetHeight : 0;
                delete details.dataset.closing;
                details.open = true;
                animation = gsap.timeline()
                    .fromTo(answer, { height: from, autoAlpha: from ? gsap.getProperty(answer, 'opacity') : 0 }, {
                        height: 'auto',
                        autoAlpha: 1,
                        duration: 0.5,
                        ease: 'power2.inOut',
                        clearProps: 'height',
                    })
                    .to(arrow, { rotation: 90, duration: 0.5, ease: 'power2.inOut', force3D: true }, 0);
            };

            const close = () => {
                animation?.kill();
                details.dataset.closing = '';
                animation = gsap.timeline({
                    onComplete: () => {
                        details.open = false;
                        delete details.dataset.closing;
                        gsap.set(answer, { clearProps: 'height,opacity,visibility' });
                    },
                })
                    .to(answer, { height: 0, autoAlpha: 0, duration: 0.4, ease: 'power2.inOut' })
                    .to(arrow, { rotation: 0, duration: 0.5, ease: 'power2.inOut', force3D: true }, 0);
            };

            return { details, summary, arrow, isOpen, open, close };
        });

        items.forEach((item) => {
            item.summary.addEventListener('click', (event) => {
                if (reduce.matches) {
                    // Accordéon natif : les autres questions refermées sans animation
                    if (!item.details.open) items.forEach((other) => other !== item && (other.details.open = false));
                    return;
                }
                event.preventDefault();
                if (item.isOpen()) {
                    item.close();
                    return;
                }
                items.forEach((other) => other !== item && other.isOpen() && other.close());
                item.open();
            });
        });
    });
}
