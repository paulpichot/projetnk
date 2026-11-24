// Bandeau d'objectif RGive (snippet components/rgive-goal) : chiffres de la campagne (route /rgive/<page>), sinon ceux
// du Panel déjà affichés ; jours restants calculés ici, la page étant en cache statique
const DAY = 86400000;

export function initializeRgiveGoal() {
    const banner = document.querySelector('[data-rgive-goal]');
    const controller = new AbortController();

    const render = ({ contributors, goal, deadline }) => {
        const count = contributors ?? Number(banner.querySelector('[data-goal-count]').textContent);
        const target = goal ?? Number(banner.querySelector('[data-goal-target]').textContent);
        const end = deadline ?? Number(banner.dataset.deadline);
        if (!target) return;

        banner.querySelectorAll('[data-goal-target]').forEach((element) => (element.textContent = target));
        banner.querySelector('[data-goal-count]').textContent = count;
        banner.querySelector('[data-goal-days]').textContent = end ? Math.max(0, Math.ceil((end - Date.now()) / DAY)) : '…';
        banner.querySelector('[data-goal-bar]').style.width = `${Math.min(100, (count / target) * 100)}%`;
        const meter = banner.querySelector('[role=meter]');
        meter.setAttribute('aria-valuenow', count);
        meter.setAttribute('aria-valuemax', target);
        banner.hidden = false;
    };

    fetch(banner.dataset.rgiveGoal, { signal: controller.signal })
        .then((response) => (response.ok ? response.json() : {}))
        .catch(() => ({}))
        .then((data) => controller.signal.aborted || render(data));

    return () => controller.abort();
}
