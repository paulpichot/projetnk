// Formulaires newsletter (snippet components/newsletter), comme sur le countdown : envoi sans rechargement
// vers la route /newsletter, états sending / success / error posés en data-state sur le formulaire
export function initializeNewsletter() {
    document.querySelectorAll('[data-newsletter]').forEach((form) => {
        const input = form.querySelector('input');
        const button = form.querySelector('button');
        const label = button.textContent;
        const message = form.parentElement.querySelector('[data-newsletter-message]');

        const setState = (state, text = label) => {
            form.dataset.state = state;
            button.textContent = text;
            button.disabled = state === 'sending' || state === 'success';
        };

        input.addEventListener('input', () => {
            if (form.dataset.state) {
                setState('');
                message.textContent = '';
            }
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            setState('sending', button.dataset.sending);
            message.textContent = '';

            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { Accept: 'application/json' },
            }).catch((error) => ({ json: () => ({ detail: error.message }) }));

            const data = await response.json().catch(() => ({ detail: `Erreur serveur ${response.status}` }));

            if (data.ok) {
                form.reset();
                setState('success', button.dataset.success);
                message.textContent = data.message;
                return;
            }

            if (data.detail) console.error(data.detail);
            message.textContent = data.message ?? 'Un souci technique nous empêche de vous inscrire, réessayez plus tard.';
            setState('error');
        });
    });
}
