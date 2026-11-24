import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Flottement au rythme du scroll, comme les boules du logo
// data-parallax = amplitude verticale en px, data-parallax-rotate = rotation en degrés (signe = sens),
// data-parallax-angle = angle de départ de l'élément
export function initializeParallax() {
    gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
        document.querySelectorAll('[data-parallax]').forEach((element) => {
            const distance = Number(element.dataset.parallax);
            const rotation = Number(element.dataset.parallaxRotate ?? 0);
            const angle = Number(element.dataset.parallaxAngle ?? 0);

            gsap.fromTo(element, { y: distance, rotation: angle - rotation }, {
                y: -distance,
                rotation: angle + rotation,
                ease: 'none',
                scrollTrigger: { trigger: element.parentElement, start: 'top bottom', end: 'bottom top', scrub: 1.5 },
            });
        });
    });
}
