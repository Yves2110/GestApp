/**
 * GestApp — Wrapper fetch AJAX
 * Injecte automatiquement le token CSRF et les headers JSON.
 */
import { getCsrfToken } from './helpers.js';

const defaultHeaders = () => ({
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': getCsrfToken(),
    'X-Requested-With': 'XMLHttpRequest',
});

async function request(method, url, data = null, opts = {}) {
    const config = {
        method,
        headers: { ...defaultHeaders(), ...(opts.headers || {}) },
        ...opts,
    };
    if (data && method !== 'GET') {
        config.body = JSON.stringify(data);
    }
    const response = await fetch(url, config);
    if (!response.ok) {
        const err = await response.json().catch(() => ({ message: response.statusText }));
        throw Object.assign(new Error(err.message || 'Erreur réseau'), { status: response.status, data: err });
    }
    const text = await response.text();
    return text ? JSON.parse(text) : null;
}

const api = {
    get:    (url, opts)       => request('GET',    url, null, opts),
    post:   (url, data, opts) => request('POST',   url, data, opts),
    put:    (url, data, opts) => request('PUT',    url, data, opts),
    patch:  (url, data, opts) => request('PATCH',  url, data, opts),
    delete: (url, opts)       => request('DELETE', url, null, opts),
};

export default api;
window.api = api;
