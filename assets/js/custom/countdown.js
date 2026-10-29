// Compte à rebours jusqu'à la date de lancement (data-countdown)
const UNITS = { days: 86400, hours: 3600, minutes: 60, seconds: 1 };

export function initializeCountdown() {
    const countdown = document.querySelector('[data-countdown]');
    const target = new Date(countdown.dataset.countdown).getTime();
    const values = [...countdown.querySelectorAll('[data-unit]')];

    const update = () => {
        let rest = Math.max(0, Math.floor((target - Date.now()) / 1000));
        values.forEach((value) => {
            const size = UNITS[value.dataset.unit];
            value.textContent = String(Math.floor(rest / size)).padStart(2, '0');
            rest %= size;
        });
    };

    update();
    setInterval(update, 1000);
}
