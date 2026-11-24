// Remplace l'aperçu par le lecteur au clic sur « lecture »
export function initializeVideo() {
    document.querySelectorAll('[data-video]').forEach((video) => {
        video.querySelector('.video__play').addEventListener('click', () => {
            video.replaceChildren(video.querySelector('template').content.cloneNode(true));
        });
    });
}
