/**
 * GestApp — Toast notification system
 * Usage: Toast.success('Enregistré !') | Toast.error('Erreur') | Toast.warning('...') | Toast.info('...')
 */
const Toast = (() => {
    const ICONS = {
        success: 'bi-check-circle-fill',
        error:   'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info:    'bi-info-circle-fill',
    };
    const TITLES = {
        success: 'Succès',
        error:   'Erreur',
        warning: 'Attention',
        info:    'Information',
    };

    function getContainer() {
        let c = document.getElementById('toastContainer');
        if (!c) {
            c = document.createElement('div');
            c.id = 'toastContainer';
            c.className = 'toast-container';
            c.setAttribute('aria-live', 'polite');
            document.body.appendChild(c);
        }
        return c;
    }

    function show(type, message, title, duration = 4000) {
        const container = getContainer();
        const toast = document.createElement('div');
        toast.className = `app-toast app-toast--${type} fade-in`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <i class="bi ${ICONS[type] || ICONS.info} toast-icon"></i>
            <div class="toast-body">
                <div class="toast-title">${title || TITLES[type]}</div>
                ${message ? `<div class="toast-message">${message}</div>` : ''}
            </div>
            <button class="toast-close" aria-label="Fermer">&times;</button>
        `;

        container.appendChild(toast);

        toast.querySelector('.toast-close').addEventListener('click', () => dismiss(toast));

        if (duration > 0) {
            setTimeout(() => dismiss(toast), duration);
        }
        return toast;
    }

    function dismiss(toast) {
        toast.classList.add('dismissing');
        toast.addEventListener('animationend', () => toast.remove(), { once: true });
    }

    return {
        success: (msg, title, dur) => show('success', msg, title, dur),
        error:   (msg, title, dur) => show('error',   msg, title, dur),
        warning: (msg, title, dur) => show('warning', msg, title, dur),
        info:    (msg, title, dur) => show('info',    msg, title, dur),
    };
})();

export default Toast;
window.Toast = Toast;
