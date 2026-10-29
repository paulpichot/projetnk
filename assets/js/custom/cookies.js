// Gestion des cookies : enregistre les choix dans un cookie "consent" (6 mois)
const NAME = 'consent';
const MAX_AGE = 60 * 60 * 24 * 182;

const read = () => {
    const match = document.cookie.match(new RegExp(`(?:^|; )${NAME}=([^;]*)`));
    return match ? JSON.parse(decodeURIComponent(match[1])) : {};
};

const write = (choices) => {
    document.cookie = `${NAME}=${encodeURIComponent(JSON.stringify(choices))}; max-age=${MAX_AGE}; path=/; SameSite=Lax`;
};

export function initializeCookies() {
    const form = document.querySelector('[data-cookies]');
    const switches = [...form.querySelectorAll('.switch')];
    const choices = read();

    const save = () => write(Object.fromEntries(switches.map((input) => [input.name, input.checked])));

    switches.forEach((input) => { input.checked = choices[input.name] === true; });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        save();
    });

    form.querySelectorAll('[data-consent]').forEach((button) => {
        button.addEventListener('click', () => {
            switches.forEach((input) => { input.checked = button.dataset.consent === 'all'; });
            save();
        });
    });
}
