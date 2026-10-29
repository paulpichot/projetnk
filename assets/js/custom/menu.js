// Menu mobile : ouverture / fermeture du panneau de navigation
export function initializeMenu() {
    const toggle = document.querySelector('.header__toggle');
    const root = document.documentElement;

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', open);
        toggle.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        root.classList.toggle('is-menu-open', open);
    };

    toggle.addEventListener('click', () => setOpen(!root.classList.contains('is-menu-open')));
    document.addEventListener('keydown', (e) => e.key === 'Escape' && setOpen(false));
}
