import { gsap } from 'gsap';
import { Observer } from 'gsap/Observer';

gsap.registerPlugin(Observer);

// Écran d'arrivée de l'accueil (une fois par visite, classe intro posée dans head.php), au-dessus du site :
// logo en grand et menu centrés. Le premier geste de scroll, ou un clic sur un lien vers l'accueil,
// les renvoie à leur place dans l'en-tête pendant que l'écran s'efface, puis onComplete est appelé.
// Le grand logo est une copie de celui de l'en-tête dessinée directement à sa taille (aucun agrandissement par
// transformation, que Safari rend flou) ; elle rétrécit jusqu'au logo de l'en-tête, qui reprend alors sa place.
// Un clic sur un lien vers une autre page joue le même mouvement en prenant la couleur de fond de cette page
// (data-background), puis l'ouvre (transition entre pages de navigation.js).
export function playIntro(onComplete) {
    const root = document.documentElement;
    const logo = document.querySelector('.header__logo');
    const nav = document.querySelector('.nav');
    const screen = document.querySelector('.intro-screen');

    // Page rechargée en cours de lecture : pas d'écran d'arrivée
    if (window.scrollY > 0) {
        screen.remove();
        gsap.set([logo, nav], { autoAlpha: 1 });
        onComplete();
        return () => {};
    }

    // Grand logo : même lien que celui de l'en-tête (cliquable de la même façon), au-dessus de l'écran
    const big = logo.cloneNode(true);
    big.className = 'fixed z-3 block *:block *:h-auto *:w-full';
    big.setAttribute('aria-hidden', 'true');
    big.tabIndex = -1;
    document.body.append(big);

    const place = () => {
        gsap.set(nav, { clearProps: 'transform' });
        const logoRect = logo.getBoundingClientRect();
        const navRect = nav.getBoundingClientRect();
        const width = root.clientWidth; // largeur visible, barre de défilement exclue
        const size = Math.min(616, width * 0.6); // 616px : largeur du logo du countdown
        const height = (logoRect.height * size) / logoRect.width;
        const centerY = window.innerHeight * 0.42;

        // Position arrondie au pixel : tracé net
        gsap.set(big, { left: Math.round((width - size) / 2), top: Math.round(centerY - height / 2), width: size, clearProps: 'transform' });
        gsap.set(nav, {
            x: width / 2 - (navRect.left + navRect.width / 2),
            y: centerY + height / 2 + 48 - navRect.top,
        });
    };

    place();
    gsap.set(nav, { autoAlpha: 1 });
    root.classList.add('is-intro');
    window.addEventListener('resize', place);

    // Le logo de l'en-tête prend le relais du grand logo arrivé à sa place
    const swap = () => {
        gsap.set(logo, { autoAlpha: 1 });
        big.remove();
    };
    const reveal = () => {
        release();
        root.classList.remove('is-intro', 'intro');
        screen.remove();
    };

    let leaving;
    const leave = (link) => {
        if (leaving) return;
        cleanup();
        try {
            sessionStorage.setItem('intro-vue', '1');
        } catch {}

        // Vers une autre page : nouveau clic sur le lien une fois le mouvement fini (ses écouteurs retirés, il n'est plus
        // intercepté ici), l'écran partant avec le contenu de l'accueil
        const from = big.getBoundingClientRect();
        const to = logo.getBoundingClientRect();
        leaving = gsap.timeline({ onComplete: () => (link ? link.click() : reveal()) })
            .to(big, {
                x: to.left - from.left,
                y: to.top - from.top,
                scale: to.width / from.width,
                transformOrigin: '0 0',
                duration: 1.2,
                ease: 'power3.inOut',
                onComplete: swap,
            })
            .to(nav, { x: 0, y: 0, duration: 1.2, ease: 'power3.inOut' }, '<0.08');

        if (link) {
            if (link.dataset.background) {
                const color = getComputedStyle(root).getPropertyValue(`--color-${link.dataset.background}`);
                leaving.to(screen, { backgroundColor: color, duration: 1.2, ease: 'power2.inOut' }, 0);
            }
            return;
        }
        leaving
            .to(screen, { autoAlpha: 0, duration: 0.9, ease: 'power2.inOut' }, '<0.3')
            .call(onComplete, null, '-=0.2');
    };
    const onKey = (event) => {
        if (['ArrowDown', 'PageDown', ' ', 'End'].includes(event.key)) {
            event.preventDefault();
            leave();
        }
    };
    // Clic simple sur le logo ou un lien du menu (ouverture dans un nouvel onglet laissée au navigateur)
    const onLinkClick = (event) => {
        if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        // Bouton de la Capsule RGive : la Capsule s'ouvre par-dessus l'écran, qui reste en place
        if (event.currentTarget.classList.contains('rgive-button')) return;
        event.preventDefault();
        const url = new URL(event.currentTarget.href);
        leave(url.pathname === window.location.pathname ? null : event.currentTarget);
    };
    // Page laissée défilable sous l'écran (sa barre de défilement reste affichée : la masquer puis la rendre décalait
    // tout de sa largeur au départ) ; molette, tactile et clavier sont interceptés, la barre tirée à la souris ramène en haut.
    // Blocage maintenu pendant le départ (release, à la disparition de l'écran) : l'inertie du geste qui l'a déclenché
    // ferait sinon défiler la page, et l'en-tête avec, pendant que logo et menu le rejoignent
    const onScroll = () => window.scrollY > 0 && window.scrollTo({ top: 0, behavior: 'instant' });
    const links = [logo, big, ...nav.querySelectorAll('a')];
    let lastWheel = 0;
    const observer = Observer.create({
        type: 'wheel,touch',
        preventDefault: true,
        tolerance: 10,
        onChangeY: () => leave(),
        onWheel: () => (lastWheel = performance.now()),
    });
    window.addEventListener('keydown', onKey);
    window.addEventListener('scroll', onScroll);
    links.forEach((link) => link.addEventListener('click', onLinkClick));

    function cleanup() {
        window.removeEventListener('resize', place);
        links.forEach((link) => link.removeEventListener('click', onLinkClick));
    }

    // Défilement rendu une fois le geste fini (200ms sans molette, 0,8s au plus) : la fin de l'inertie ne fait pas
    // repartir la page sous l'en-tête à peine en place ; immédiatement en quittant la page
    let releasing;
    function release(now = false) {
        const done = () => {
            clearTimeout(releasing);
            observer.kill();
            window.removeEventListener('keydown', onKey);
            window.removeEventListener('scroll', onScroll);
        };
        const until = performance.now() + 800;
        const wait = () => (performance.now() - lastWheel > 200 || performance.now() > until ? done() : (releasing = setTimeout(wait, 50)));
        now ? done() : wait();
    }

    return () => {
        cleanup();
        leaving?.kill();
        swap();
        reveal();
        release(true);
        gsap.set(nav, { clearProps: 'transform' });
    };
}
