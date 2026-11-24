// Formulaire de don RGive intégré (élément data-rgive-src, support.php) : script embed.js de RGive chargé à chaque arrivée
// sur la page, y compris par la navigation sans rechargement (il ne repère les formulaires qu'à son exécution)
export function initializeRgiveEmbed() {
    const element = document.querySelector('[data-rgive-src]');
    if (!element?.dataset.rgiveScript || element.querySelector('iframe')) return;
    const script = document.createElement('script');
    script.src = element.dataset.rgiveScript;
    script.async = true;
    document.head.append(script);
    return () => script.remove();
}
