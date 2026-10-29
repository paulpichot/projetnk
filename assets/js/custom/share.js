// Copie le lien de la page dans le presse-papiers
export function initializeShare() {
    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', () => navigator.clipboard.writeText(button.dataset.copy));
    });
}
