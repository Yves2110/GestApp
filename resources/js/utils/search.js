/**
 * GestApp — Recherche globale (Ctrl+K / ⌘K)
 */
import { debounce } from './helpers.js';
import api from './api.js';

const SEARCH_URL = '/api/search';
const MIN_LEN    = 2;

function buildModal() {
    const el = document.createElement('div');
    el.id = 'globalSearchModal';
    el.innerHTML = `
        <div class="global-search-backdrop"></div>
        <div class="global-search-dialog" role="dialog" aria-modal="true" aria-label="Recherche globale">
            <div class="global-search-input-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="globalSearchInput" class="global-search-input"
                       placeholder="Rechercher une activité, un objectif…" autocomplete="off" spellcheck="false">
                <kbd class="global-search-kbd">Échap</kbd>
            </div>
            <div class="global-search-results" id="globalSearchResults">
                <div class="global-search-hint text-muted text-sm py-4 text-center">
                    Commencez à saisir pour rechercher…
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(el);
    return el;
}

function renderResults(data) {
    const container = document.getElementById('globalSearchResults');
    if (!data || data.length === 0) {
        container.innerHTML = '<div class="global-search-empty py-4 text-center text-muted text-sm"><i class="bi bi-inbox me-2"></i>Aucun résultat</div>';
        return;
    }
    const groups = {};
    data.forEach(r => {
        groups[r.type] = groups[r.type] || [];
        groups[r.type].push(r);
    });
    const typeLabels = { activity: 'Activités', objective: 'Objectifs', service: 'Services' };
    let html = '';
    for (const [type, items] of Object.entries(groups)) {
        html += `<div class="global-search-group-label">${typeLabels[type] || type}</div>`;
        items.forEach(item => {
            html += `
                <a href="${item.url}" class="global-search-result-item">
                    <div class="global-search-result-icon">
                        <i class="bi ${item.icon || 'bi-file-text'}"></i>
                    </div>
                    <div class="global-search-result-body">
                        <div class="global-search-result-title">${item.title}</div>
                        ${item.subtitle ? `<div class="global-search-result-sub text-xs text-muted">${item.subtitle}</div>` : ''}
                    </div>
                    ${item.badge ? `<span class="badge badge-secondary ms-auto text-xs">${item.badge}</span>` : ''}
                </a>`;
        });
    }
    container.innerHTML = html;
}

function initGlobalSearch() {
    const modal = buildModal();
    const input = document.getElementById('globalSearchInput');
    const backdrop = modal.querySelector('.global-search-backdrop');

    const open  = () => { modal.classList.add('active'); input.focus(); input.select(); };
    const close = () => { modal.classList.remove('active'); };

    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); open(); }
        if (e.key === 'Escape') close();
    });

    const searchBtn = document.getElementById('globalSearchBtn');
    if (searchBtn) searchBtn.addEventListener('click', open);

    const doSearch = debounce(async (q) => {
        if (q.length < MIN_LEN) {
            document.getElementById('globalSearchResults').innerHTML =
                '<div class="global-search-hint text-muted text-sm py-4 text-center">Commencez à saisir pour rechercher…</div>';
            return;
        }
        try {
            const data = await api.get(`${SEARCH_URL}?q=${encodeURIComponent(q)}`);
            renderResults(data);
        } catch {
            document.getElementById('globalSearchResults').innerHTML =
                '<div class="text-muted text-sm py-4 text-center">Erreur lors de la recherche.</div>';
        }
    }, 250);

    input.addEventListener('input', () => doSearch(input.value.trim()));

    // Navigation clavier dans les résultats
    input.addEventListener('keydown', e => {
        const items = [...document.querySelectorAll('.global-search-result-item')];
        const focused = document.querySelector('.global-search-result-item:focus');
        const idx = items.indexOf(focused);
        if (e.key === 'ArrowDown') { e.preventDefault(); (items[idx + 1] || items[0])?.focus(); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); (items[idx - 1] || items[items.length - 1])?.focus(); }
        if (e.key === 'Enter' && idx === -1 && items[0]) items[0].click();
    });
}

export { initGlobalSearch };
