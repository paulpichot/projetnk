import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

// Apparitions déclenchées une seule fois à l'entrée dans l'écran (rien ne s'efface en remontant)
// - line : filets tracés de gauche à droite, en décalé au sein d'un même [data-reveal-group]
// - text : texte affiché mot par mot, juste après le filet de même rang ; les filets s'enchaînent
//   (le 2 part quand le 1 est tracé) pendant que chaque texte suit le sien
// - fade : éléments regroupés par ligne visuelle, chaque ligne apparaît de gauche à droite en décalé
// - tous les autres blocs de texte du contenu (hors pied de page) : mot par mot, en décalé
//   quand plusieurs entrent ensemble dans l'écran ; data-reveal="load" les anime dès l'arrivée sur la page
//   (en même temps que la frappe du titre du hero s'il y en a un), data-reveal="none" les laisse affichés sans animation
// - box : bloc (bandeau, carte) qui monte en fondu, puis ses textes mot par mot ; en décalé
//   quand plusieurs entrent ensemble dans l'écran
// - bg : fond de section qui apparaît en fondu depuis celui de la page
const TEXT = 'h1, h2, h3, h4, p, li, blockquote, figcaption, dt, dd';
// Apparition des mots, discrète : fondu et très léger glissement vers le haut
const WORDS_FROM = { autoAlpha: 0, y: 4 };
const WORDS_TO = { autoAlpha: 1, y: 0, duration: 0.9, ease: 'power2.out' };
// Déjà animés ailleurs (titre tapé du hero, filets, membres) ou réécrits par le JS
const EXCLUDED = '[data-reveal]:not([data-reveal="load"], [data-reveal="box"]), [data-reveal-group], .hero, [data-countdown], [data-newsletter], [aria-live]';

// Seuil à 95 % de l'écran (dès l'entrée par le bas), ramené juste avant la fin de page pour les éléments du bas
// (déjà visibles au chargement : seuil négatif, ils apparaissent aussitôt)
const entering = ({ trigger }) => Math.min(
    trigger.getBoundingClientRect().top + window.scrollY - window.innerHeight * 0.95,
    ScrollTrigger.maxScroll(window) - 1,
);

export function initializeReveal() {
    // La mise en page change au breakpoint : les lignes sont recalculées
    gsap.matchMedia().add({
        motion: '(prefers-reduced-motion: no-preference)',
        desktop: '(min-width: 1024px)',
    }, ({ conditions }) => {
        if (!conditions.motion) return;
        revealLines();
        revealRows();
        revealTexts();
        revealBackgrounds();
    });
}

function revealBackgrounds() {
    document.querySelectorAll('main [data-reveal="bg"]').forEach((section) => {
        // Couleur réelle lue une fois le masquage du CSS levé (index.css), puis partie de la même couleur transparente
        section.dataset.revealed = '';
        const color = gsap.utils.splitColor(getComputedStyle(section).backgroundColor);
        gsap.fromTo(section, { backgroundColor: `rgba(${color.slice(0, 3)}, 0)` }, {
            backgroundColor: `rgb(${color.slice(0, 3)})`,
            duration: 1.4,
            ease: 'power2.out',
            clearProps: 'backgroundColor',
            scrollTrigger: { trigger: section, start: entering, once: true },
        });
    });
}

function revealLines() {
    const groups = new Map();
    document.querySelectorAll('[data-reveal="line"], [data-reveal="text"]').forEach((element) => {
        const group = element.closest('[data-reveal-group]') ?? element;
        groups.set(group, [...(groups.get(group) ?? []), element]);
    });

    groups.forEach((elements, group) => {
        const lines = elements.filter((element) => element.dataset.reveal === 'line');
        const texts = elements.filter((element) => element.dataset.reveal === 'text');
        const timeline = gsap.timeline({
            scrollTrigger: { trigger: group, start: 'top 85%', once: true },
        });

        lines.forEach((line, index) => {
            const start = index * 0.6;
            timeline.fromTo(line, { scaleX: 0 }, {
                scaleX: 1,
                transformOrigin: 'left center',
                duration: 0.7,
                ease: 'power2.inOut',
            }, start);

            if (!texts[index]) return;
            const { words } = SplitText.create(texts[index], { type: 'words', aria: 'none' });
            timeline.fromTo(words, WORDS_FROM, { ...WORDS_TO, duration: 0.7, stagger: 0.02 }, start + 0.35);
        });
    });
}

function revealRows() {
    const elements = [...document.querySelectorAll('[data-reveal="fade"]')]
        .map((element) => {
            const { top, left } = element.getBoundingClientRect();
            return { element, top: top + window.scrollY, left };
        })
        .sort((a, b) => a.top - b.top);

    // Éléments alignés à quelques pixels près = même ligne
    const rows = [];
    elements.forEach((item) => {
        const row = rows.at(-1);
        if (row && item.top - row.top < 16) row.items.push(item);
        else rows.push({ top: item.top, items: [item] });
    });

    rows.forEach(({ items }) => {
        const row = items.sort((a, b) => a.left - b.left).map(({ element }) => element);

        gsap.fromTo(row, { autoAlpha: 0, y: 40 }, {
            autoAlpha: 1,
            y: 0,
            duration: 1.8,
            ease: 'expo.out',
            stagger: 0.15,
            scrollTrigger: { trigger: row[0], start: 'top 90%', once: true },
        });
    });
}

function revealTexts() {
    const matches = [...document.querySelectorAll(`main :is(${TEXT})`)].filter((block) => !block.closest(EXCLUDED));
    // Blocs de texte les plus profonds (un <li> contenant un <p> : seul le <p> est découpé)
    const blocks = matches.filter((block) => !block.querySelector(TEXT) && block.textContent.trim());
    // Masqués par le CSS jusqu'ici (index.css), les mots prennent le relais
    if (matches.length) gsap.set(matches, { visibility: 'visible' });

    const reveals = new Map(blocks.map((block) => {
        // Dans un conteneur flex ou grid, les mots découpés perdraient leurs espaces : le bloc apparaît d'un tenant
        const words = /flex|grid/.test(getComputedStyle(block).display)
            ? [block]
            : SplitText.create(block, { type: 'words', aria: 'none' }).words;
        // Durée totale bornée : un long paragraphe n'apparaît pas plus lentement qu'une phrase
        return [block, gsap.fromTo(words, WORDS_FROM, { ...WORDS_TO, stagger: Math.min(0.03, 0.6 / words.length), paused: true })];
    }));
    const onLoad = blocks.filter((block) => block.closest('[data-reveal="load"]'));
    whenHeroStarts(() => onLoad.forEach((block, index) => reveals.get(block).delay(0.2 + index * 0.12).restart(true)));

    // Blocs : leurs textes partent avec eux, juste après leur montée
    const boxes = [...document.querySelectorAll('main [data-reveal="box"]')];
    if (boxes.length) gsap.set(boxes, { autoAlpha: 0, y: 30 });
    const showBox = (box, delay) => {
        gsap.to(box, { autoAlpha: 1, y: 0, duration: 1.2, ease: 'expo.out', delay });
        blocks
            .filter((block) => block.closest('[data-reveal="box"]') === box)
            .forEach((block, index) => reveals.get(block).delay(delay + 0.3 + index * 0.12).restart(true));
    };

    const config = { start: entering, once: true };
    ScrollTrigger.batch(boxes, { ...config, onEnter: (batch) => batch.forEach((box, index) => showBox(box, index * 0.15)) });
    ScrollTrigger.batch(blocks.filter((block) => !onLoad.includes(block) && !block.closest('[data-reveal="box"]')), {
        ...config,
        onEnter: (batch) => batch.forEach((block, index) => reveals.get(block).delay(index * 0.12).restart(true)),
    });
}

// Animation du hero lancée (hero.js : dès l'arrivée, ou après l'écran d'intro), ou tout de suite s'il n'y en a pas
function whenHeroStarts(callback) {
    const title = document.querySelector('.hero__title');
    if (!title || 'started' in title.dataset) callback();
    else title.addEventListener('started', callback, { once: true });
}
