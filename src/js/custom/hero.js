import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { playIntro } from './intro';

gsap.registerPlugin(ScrollTrigger, SplitText);

export function initializeHero() {
    const hero = document.querySelector('.hero');
    const title = hero.querySelector('.hero__title');
    const image = hero.querySelector('img');
    // Relevée avant l'épinglage, qui enveloppe le hero dans un conteneur (il n'a alors plus de voisin)
    const next = hero.nextElementSibling;

    // Hero épinglé une fois son bas atteint : la section suivante glisse par-dessus
    ScrollTrigger.create({
        trigger: hero,
        start: () => (hero.offsetHeight > window.innerHeight ? 'bottom bottom' : 'top top'),
        endTrigger: next,
        end: 'top top',
        pin: true,
        pinSpacing: false,
    });

    // Invitation à défiler : fondu après la frappe du titre, seulement si le premier paragraphe de « Qui sommes-nous »
    // n'est pas entièrement visible (la section peut affleurer sans son texte, sous sa marge haute) ; légère
    // oscillation, puis effacement au premier défilement.
    // Posée au bas de l'image (même marge qu'en bas de l'écran) quand celui-ci est visible, en bas de l'écran sinon
    const cue = document.querySelector('.hero__scroll');
    const about = document.querySelector('#qui-sommes-nous');
    const intro = about?.querySelector('p') ?? about;
    // Cadre de l'image (l'image elle-même glisse pendant son apparition)
    const frame = image?.parentElement ?? hero;
    const placeCue = () => {
        cue.style.bottom = '';
        const margin = parseFloat(getComputedStyle(cue).bottom);
        cue.style.bottom = `${Math.max(margin, window.innerHeight - frame.getBoundingClientRect().bottom + margin)}px`;
    };
    const hideCue = () => {
        if (window.scrollY < 20) return;
        window.removeEventListener('scroll', hideCue);
        window.removeEventListener('resize', placeCue);
        gsap.to(cue, { autoAlpha: 0, duration: 0.4, ease: 'power1.out', overwrite: true });
    };
    const showCue = (motion) => {
        if (!cue || !about || window.scrollY > 20 || intro.getBoundingClientRect().bottom <= window.innerHeight) return;
        placeCue();
        gsap.fromTo(cue, { autoAlpha: 0, y: motion ? 8 : 0 }, { autoAlpha: 1, y: 0, duration: 0.8, ease: 'power2.out' });
        if (motion) gsap.to(cue, { y: 6, duration: 1, ease: 'sine.inOut', repeat: -1, yoyo: true, delay: 0.8 });
        window.addEventListener('scroll', hideCue, { passive: true });
        window.addEventListener('resize', placeCue);
    };
    // Défilement doux jusqu'à la section, sans ajouter d'ancre à l'adresse
    const onCueClick = (event) => {
        event.preventDefault();
        window.scrollTo({ top: about.getBoundingClientRect().top + window.scrollY, behavior: 'smooth' });
    };
    cue?.addEventListener('click', onCueClick);
    const stopCue = () => {
        window.removeEventListener('scroll', hideCue);
        window.removeEventListener('resize', placeCue);
        if (cue) cue.style.bottom = '';
        cue?.removeEventListener('click', onCueClick);
    };

    // Titre : tapé lettre par lettre derrière un curseur, au rythme irrégulier d'un clavier,
    // une fois l'écran d'arrivée passé (desktop, première visite) ou dès le chargement
    // (libellé posé à la main : celui de SplitText colle les mots séparés par un <br>)
    title.setAttribute('aria-label', title.innerText.replace(/\s+/g, ' ').trim());

    gsap.matchMedia()
        // reduce : sans lui, aucune requête ne correspond sous 1024px quand les animations sont réduites, le rappel ne
        // serait jamais appelé et le titre resterait masqué
        .add({
            motion: '(prefers-reduced-motion: no-preference)',
            reduce: '(prefers-reduced-motion: reduce)',
            desktop: '(min-width: 1024px)',
        }, ({ conditions }) => {
            if (!conditions.motion) {
                gsap.set(title, { autoAlpha: 1 });
                gsap.delayedCall(1, showCue, [false]);
                return stopCue;
            }

            // Nettoyages au changement de conditions (matchMedia)
            const cleanups = [];
            const stop = () => {
                cleanups.forEach((cleanup) => cleanup());
                stopCue();
            };

            // Parallaxe légère : l'image (son cadre, l'image elle-même ayant sa propre apparition) descend de 8 % de sa
            // hauteur entre le haut de la page et la fin de l'épinglage ; elle défile moins vite que le titre, puis
            // s'enfonce doucement pendant que « Qui sommes-nous » la recouvre.
            // Animation CSS liée au défilement (.hero-parallax, index.css) : le navigateur la calcule à chaque image du
            // scroll, hors du fil JavaScript, sans le décalage d'une animation pilotée en JS. Navigateurs sans cette
            // fonction (Firefox) : GSAP, collé au scroll
            if (image) {
                if (CSS.supports('animation-timeline: scroll()')) {
                    const setEnd = () => frame.style.setProperty('--parallax-end', `${next.getBoundingClientRect().top + window.scrollY}px`);
                    setEnd();
                    frame.classList.add('hero-parallax');
                    ScrollTrigger.addEventListener('refresh', setEnd);
                    cleanups.push(() => {
                        ScrollTrigger.removeEventListener('refresh', setEnd);
                        frame.classList.remove('hero-parallax');
                        frame.style.removeProperty('--parallax-end');
                    });
                } else {
                    gsap.to(frame, {
                        yPercent: 8,
                        ease: 'none',
                        force3D: true,
                        scrollTrigger: { start: 0, endTrigger: next, end: 'top top', scrub: true },
                    });
                }
            }

            // Image : fondu lent avec une très légère montée, en même temps que la frappe du titre
            const showImage = image && gsap.fromTo(image, { autoAlpha: 0, y: 16 }, { autoAlpha: 1, y: 0, duration: 2, ease: 'power2.out', paused: true });

            const { chars } = SplitText.create(title, { type: 'words,chars', wordsClass: 'whitespace-nowrap', aria: 'hidden' });

            // Curseur posé par-dessus le texte (hors du flux, il ne décale aucune lettre)
            const caret = document.createElement('span');
            caret.className = 'absolute top-0 left-0 w-[0.08em] bg-corail';
            title.append(caret);
            const moveCaret = (char, edge = 'right') => {
                const box = title.getBoundingClientRect();
                const rect = char.getBoundingClientRect();
                gsap.set(caret, { x: rect[edge] - box.left, y: rect.top - box.top, height: rect.height });
            };

            gsap.set([chars, caret], { autoAlpha: 0 });
            gsap.set(title, { autoAlpha: 1 });
            moveCaret(chars[0], 'left');

            // Curseur qui clignote une fois (même cadence qu'à la fin) avant la première lettre
            const blink = { autoAlpha: 0, duration: 0.45, ease: 'steps(1)', yoyo: true };
            const timeline = gsap.timeline({ paused: true, delay: 0.2 })
                .set(caret, { autoAlpha: 1 }, 0)
                .to(caret, { ...blink, repeat: 1 }, 0);
            let time = 2 * blink.duration;
            chars.forEach((char) => {
                timeline.set(char, { autoAlpha: 1 }, time).call(() => moveCaret(char), null, time);
                time += gsap.utils.random(0.025, 0.055);
                if (/[.,]/.test(char.textContent)) time += 0.15;
                if (char === char.parentElement.lastElementChild && char.parentElement.nextSibling?.nodeName === 'BR') time += 0.1;
            });
            timeline
                .call(showCue, [true], time + 0.4)
                .to(caret, { ...blink, repeat: 5 }, time + 0.2)
                .to(caret, { autoAlpha: 0, duration: 0.3 });

            // Page découverte (dès l'arrivée, ou une fois l'écran d'intro parti) : frappe du titre, image, et signal
            // aux textes qui l'attendent (data-reveal="load", reveal.js)
            const start = () => {
                timeline.play();
                showImage?.play();
                title.dataset.started = '';
                title.dispatchEvent(new Event('started'));
            };
            if (!conditions.desktop || !document.documentElement.classList.contains('intro')) {
                start();
                return stop;
            }
            const stopIntro = playIntro(start);
            return () => {
                stopIntro();
                stop();
            };
        });
}
