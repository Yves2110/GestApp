/**
 * GestApp — Helpers JS
 */

/** Formater un nombre en devise locale */
export function formatCurrency(amount, currency = 'XOF') {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency, maximumFractionDigits: 0 })
        .format(amount);
}

/** Formater une date */
export function formatDate(date, opts = { day: '2-digit', month: '2-digit', year: 'numeric' }) {
    return new Intl.DateTimeFormat('fr-FR', opts).format(new Date(date));
}

/** Debounce */
export function debounce(fn, delay = 300) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}

/** Récupérer le token CSRF depuis le meta tag */
export function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

/** Tronquer un texte */
export function truncate(str, max = 60) {
    return str && str.length > max ? str.slice(0, max) + '…' : str;
}

/** Capitaliser la première lettre */
export function ucfirst(str) {
    return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
}
