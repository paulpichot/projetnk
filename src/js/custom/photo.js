import { gsap } from 'gsap';

// Photo de haut de page (data-photo) : fondu lent comme l'illustration de l'accueil (hero.js), avec un léger zoom arrière
// au lieu d'une montée (photo bord à bord : une montée découvrirait le fond au-dessus). Joué une fois l'image décodée,
// pour ne pas fondre une image encore vide ; masquée d'ici là par sa classe motion-safe:in-[.js]:invisible
export function initializePhoto() {
    document.querySelectorAll('[data-photo]').forEach((image) => {
        gsap.matchMedia().add({
            motion: '(prefers-reduced-motion: no-preference)',
            reduce: '(prefers-reduced-motion: reduce)',
        }, ({ conditions }) => {
            if (conditions.reduce) {
                gsap.set(image, { autoAlpha: 1 });
                return;
            }
            const show = gsap.fromTo(image, { autoAlpha: 0, scale: 1.04 }, { autoAlpha: 1, scale: 1, duration: 2, ease: 'power2.out', paused: true });
            image.decode().catch(() => {}).then(() => show.play());
        });
    });
}
