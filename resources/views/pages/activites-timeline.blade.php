@extends('layouts.app')

@section('title', 'Timeline des activités')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('Activites') }}">Activités</a></li>
    <li class="breadcrumb-item active">Timeline</li>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   TIMELINE — Gantt CSS pur
   ═══════════════════════════════════════════════════ */

#timeline-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

/* ── Header axe temps ──────────────────────────────── */
.tl-header {
    display: flex;
    border-bottom: 2px solid var(--border-color);
    background: var(--bg-sidebar, #f8f9fa);
    position: sticky;
    top: 0;
    z-index: 10;
}
.tl-header-label {
    width: 220px;
    min-width: 220px;
    padding: .6rem 1rem;
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semi);
    text-transform: uppercase;
    color: var(--text-secondary);
    border-right: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: .5rem;
}
.tl-axis {
    flex: 1;
    display: flex;
    overflow: hidden;
}
.tl-axis-cell {
    flex: 1;
    text-align: center;
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    color: var(--text-secondary);
    padding: .5rem .25rem;
    border-right: 1px solid var(--border-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tl-axis-cell:last-child { border-right: none; }
.tl-axis-cell.tl-today {
    background: var(--clr-primary-light, #eef2ff);
    color: var(--clr-primary);
    font-weight: var(--font-weight-semi);
}

/* ── Groupes / lignes ─────────────────────────────── */
.tl-group-header {
    display: flex;
    align-items: center;
    background: var(--bg-sidebar, #f8f9fa);
    border-bottom: 1px solid var(--border-color);
}
.tl-group-label {
    width: 220px;
    min-width: 220px;
    padding: .5rem 1rem;
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-semi);
    color: var(--text-primary);
    border-right: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: .5rem;
}
.tl-group-stripe {
    flex: 1;
    height: 2rem;
    background: repeating-linear-gradient(
        90deg,
        transparent 0,
        transparent calc(var(--col-w, 60px) - 1px),
        var(--border-color) calc(var(--col-w, 60px) - 1px),
        var(--border-color) var(--col-w, 60px)
    );
}

.tl-row {
    display: flex;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
    min-height: 2.75rem;
    position: relative;
}
.tl-row:hover { background: var(--clr-primary-light, #f0f4ff)20; }
.tl-row-label {
    width: 220px;
    min-width: 220px;
    padding: .4rem 1rem;
    font-size: var(--font-size-xs);
    color: var(--text-primary);
    border-right: 1px solid var(--border-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: default;
}
.tl-row-track {
    flex: 1;
    position: relative;
    height: 2.75rem;
    background: repeating-linear-gradient(
        90deg,
        transparent 0,
        transparent calc(var(--col-w, 60px) - 1px),
        var(--border-color) calc(var(--col-w, 60px) - 1px),
        var(--border-color) var(--col-w, 60px)
    );
}

/* ── Barre Gantt ─────────────────────────────────── */
.tl-bar {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    height: 1.5rem;
    border-radius: 4px;
    display: flex;
    align-items: center;
    padding: 0 .4rem;
    font-size: .65rem;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    transition: opacity .15s, transform .15s;
    min-width: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.tl-bar:hover {
    opacity: .85;
    transform: translateY(-50%) scaleY(1.1);
}

/* ── Ligne "aujourd'hui" ─────────────────────────── */
.tl-now-line {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--clr-primary);
    opacity: .6;
    z-index: 5;
    pointer-events: none;
}
.tl-now-line::before {
    content: '';
    position: absolute;
    top: 2px;
    left: -4px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--clr-primary);
}

/* ── Empty state ─────────────────────────────────── */
#timeline-empty { padding: 3rem; text-align: center; display: none; }

/* ── Tooltip ─────────────────────────────────────── */
#tl-tooltip {
    position: fixed;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    padding: .75rem 1rem;
    z-index: 2000;
    font-size: var(--font-size-xs);
    max-width: 260px;
    pointer-events: none;
    opacity: 0;
    transition: opacity .15s;
}
#tl-tooltip.visible { opacity: 1; }
#tl-tooltip .tt-title { font-weight: 600; font-size: .8rem; margin-bottom: .35rem; color: var(--text-primary); }
#tl-tooltip .tt-row { display: flex; gap: .4rem; color: var(--text-secondary); line-height: 1.5; }
#tl-tooltip .tt-key { font-weight: 500; color: var(--text-primary); white-space: nowrap; }

/* ── Légende ──────────────────────────────────────── */
.tl-legend { display: flex; flex-wrap: wrap; gap: .75rem; }
.tl-legend-item { display: flex; align-items: center; gap: .35rem; font-size: .75rem; color: var(--text-secondary); }
.tl-legend-dot { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }

/* ── Zoom controls ────────────────────────────────── */
.tl-zoom-btn.active {
    background: var(--clr-primary);
    color: #fff;
    border-color: var(--clr-primary);
}

/* ── Loading ─────────────────────────────────────── */
#timeline-loading { padding: 2rem; text-align: center; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Timeline des activités</h1>
        <p class="page-subtitle">Diagramme de Gantt par service</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('Activites') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-list-ul"></i>
            <span class="hide-mobile">Liste</span>
        </a>
        <a href="{{ route('activites.kanban') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-kanban"></i>
            <span class="hide-mobile">Kanban</span>
        </a>
        <a href="{{ route('activites.calendar') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-calendar3"></i>
            <span class="hide-mobile">Calendrier</span>
        </a>
    </div>
</div>

{{-- Filtres + Zoom ────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label text-xs text-muted fw-semi text-uppercase">Service</label>
                <select class="form-select form-select-sm" id="filter-service">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-xs text-muted fw-semi text-uppercase">Statut workflow</label>
                <select class="form-select form-select-sm" id="filter-wf">
                    <option value="">Tous</option>
                    <option value="draft">Brouillon</option>
                    <option value="pending">En attente</option>
                    <option value="validated">Validé</option>
                    <option value="rejected">Rejeté</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-xs text-muted fw-semi text-uppercase">Période</label>
                <select class="form-select form-select-sm" id="filter-periode">
                    <option value="">Toutes</option>
                    @foreach($periodes as $p)
                        <option value="{{ $p->id }}">{{ $p->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-xs text-muted fw-semi text-uppercase">Zoom</label>
                <div class="btn-group btn-group-sm w-100">
                    <button class="btn btn-outline-secondary tl-zoom-btn" data-zoom="week">Sem.</button>
                    <button class="btn btn-outline-secondary tl-zoom-btn active" data-zoom="month">Mois</button>
                    <button class="btn btn-outline-secondary tl-zoom-btn" data-zoom="quarter">Trim.</button>
                    <button class="btn btn-outline-secondary tl-zoom-btn" data-zoom="year">An</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Légende ─────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-3 px-1">
    <div class="tl-legend">
        <div class="tl-legend-item"><span class="tl-legend-dot" style="background:#6c757d;"></span>Brouillon</div>
        <div class="tl-legend-item"><span class="tl-legend-dot" style="background:#fd7e14;"></span>En attente</div>
        <div class="tl-legend-item"><span class="tl-legend-dot" style="background:#198754;"></span>Validé</div>
        <div class="tl-legend-item"><span class="tl-legend-dot" style="background:#dc3545;"></span>Rejeté</div>
    </div>
    <button class="btn btn-ghost btn-sm" id="btn-export-png">
        <i class="bi bi-image me-1"></i> <span class="hide-mobile">Exporter PNG</span>
    </button>
</div>

{{-- Timeline ─────────────────────────────────────────────── --}}
<div id="timeline-wrapper">
    <div id="timeline-loading">
        <x-skeleton lines="6" />
    </div>
    <div id="timeline-empty">
        <i class="bi bi-diagram-3 text-muted" style="font-size:3rem;"></i>
        <p class="text-muted mt-3">Aucune activité à afficher pour ces filtres.</p>
    </div>
    <div id="timeline-inner" style="display:none; overflow-x:auto;">
        <div id="timeline-content"></div>
    </div>
</div>

{{-- Tooltip ─────────────────────────────────────────────── --}}
<div id="tl-tooltip">
    <div class="tt-title" id="tt-title"></div>
    <div class="tt-row"><span class="tt-key">Service :</span><span id="tt-service"></span></div>
    <div class="tt-row"><span class="tt-key">Objectif :</span><span id="tt-obj"></span></div>
    <div class="tt-row"><span class="tt-key">Période :</span><span id="tt-periode"></span></div>
    <div class="tt-row mt-2">
        <a id="tt-link" href="#" class="text-link text-xs">Voir le détail →</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const API_URL = '{{ route("api.timeline.data") }}';
    const CSRF    = '{{ csrf_token() }}';

    const WF_LABELS = { draft: 'Brouillon', pending: 'En attente', validated: 'Validé', rejected: 'Rejeté' };
    const WF_COLORS = { draft: '#6c757d', pending: '#fd7e14', validated: '#198754', rejected: '#dc3545' };

    let currentZoom = 'month';
    let currentData = null;

    // ── Colonnes selon zoom ──────────────────────────────
    function buildColumns(minDate, maxDate, zoom) {
        const cols = [];
        const start = new Date(minDate);
        const end   = new Date(maxDate);

        if (zoom === 'week') {
            const d = new Date(start);
            d.setDate(d.getDate() - d.getDay());
            while (d <= end) {
                cols.push({ label: formatDate(d, 'dd/MM'), date: new Date(d) });
                d.setDate(d.getDate() + 7);
            }
        } else if (zoom === 'month') {
            const d = new Date(start.getFullYear(), start.getMonth(), 1);
            while (d <= end) {
                cols.push({ label: formatDate(d, 'MMM yy'), date: new Date(d) });
                d.setMonth(d.getMonth() + 1);
            }
        } else if (zoom === 'quarter') {
            const d = new Date(start.getFullYear(), Math.floor(start.getMonth() / 3) * 3, 1);
            while (d <= end) {
                const q = Math.floor(d.getMonth() / 3) + 1;
                cols.push({ label: `T${q} ${d.getFullYear()}`, date: new Date(d) });
                d.setMonth(d.getMonth() + 3);
            }
        } else {
            const d = new Date(start.getFullYear(), 0, 1);
            while (d <= end) {
                cols.push({ label: String(d.getFullYear()), date: new Date(d) });
                d.setFullYear(d.getFullYear() + 1);
            }
        }

        if (cols.length < 2) {
            const extra = new Date(cols[cols.length - 1]?.date ?? start);
            if (zoom === 'week')    extra.setDate(extra.getDate() + 7);
            else if (zoom === 'month')   extra.setMonth(extra.getMonth() + 1);
            else if (zoom === 'quarter') extra.setMonth(extra.getMonth() + 3);
            else extra.setFullYear(extra.getFullYear() + 1);
            cols.push({ label: '…', date: extra });
        }

        return cols;
    }

    function formatDate(d, fmt) {
        const months = ['jan','fév','mar','avr','mai','jun','jul','aoû','sep','oct','nov','déc'];
        return fmt
            .replace('MMM', months[d.getMonth()])
            .replace('yy',  String(d.getFullYear()).slice(-2))
            .replace('dd',  String(d.getDate()).padStart(2,'0'))
            .replace('MM',  String(d.getMonth()+1).padStart(2,'0'));
    }

    // ── Calcul position d'une barre ──────────────────────
    function barPosition(startStr, endStr, cols) {
        const s = new Date(startStr);
        const e = new Date(endStr || startStr);
        const first = cols[0].date;
        const last  = cols[cols.length - 1].date;
        const totalMs = last - first || 1;
        const colW = 100 / (cols.length - 1 || 1);

        let left = Math.max(0, ((s - first) / totalMs) * 100);
        let right = Math.min(100, ((e - first) / totalMs) * 100);
        if (right <= left) right = left + 0.5;

        return { left: left.toFixed(2), width: (right - left).toFixed(2) };
    }

    // ── Rendu ────────────────────────────────────────────
    function render(data, zoom) {
        const cols  = buildColumns(data.min_date, data.max_date, zoom);
        const colW  = Math.max(60, Math.floor(700 / cols.length));
        const totalW = 220 + colW * cols.length;
        const today  = new Date().toISOString().slice(0, 10);
        const nowPos = barPosition(today, today, cols);

        let html = `<div style="min-width:${totalW}px;">`;

        // Header
        html += `<div class="tl-header" style="--col-w:${colW}px;">`;
        html += `<div class="tl-header-label"><i class="bi bi-building text-muted"></i>Service / Activité</div>`;
        html += `<div class="tl-axis">`;
        cols.forEach(col => {
            const isToday = col.date.toISOString().slice(0, 10) <= today &&
                            (cols[cols.indexOf(col) + 1]?.date?.toISOString().slice(0, 10) > today);
            html += `<div class="tl-axis-cell ${isToday ? 'tl-today' : ''}" style="min-width:${colW}px;max-width:${colW}px;">${col.label}</div>`;
        });
        html += `</div></div>`;

        // Groupes
        data.groups.forEach(group => {
            if (!group.items.length) return;

            html += `<div class="tl-group-header">`;
            html += `<div class="tl-group-label"><i class="bi bi-building-fill text-muted"></i>${escHtml(group.service)}</div>`;
            html += `<div class="tl-group-stripe" style="--col-w:${colW}px;flex:1;"></div>`;
            html += `</div>`;

            group.items.forEach(item => {
                const pos = barPosition(item.start, item.end, cols);
                html += `<div class="tl-row" style="position:relative;">`;
                html += `<div class="tl-row-label" title="${escHtml(item.label)}">${escHtml(item.label)}</div>`;
                html += `<div class="tl-row-track" style="--col-w:${colW}px;">`;

                // Ligne aujourd'hui
                if (parseFloat(nowPos.left) >= 0 && parseFloat(nowPos.left) <= 100) {
                    html += `<div class="tl-now-line" style="left:${nowPos.left}%;"></div>`;
                }

                // Barre
                html += `<div class="tl-bar"
                    style="left:${pos.left}%;width:${pos.width}%;background:${item.color};"
                    data-id="${item.id}"
                    data-title="${escHtml(item.label)}"
                    data-service="${escHtml(group.service)}"
                    data-obj="${escHtml(item.objective)}"
                    data-periode="${escHtml(item.periode)}"
                    data-url="${escHtml(item.url)}"
                    data-wf="${item.workflow_status}"
                >${pos.width > 5 ? escHtml(item.label) : ''}</div>`;

                html += `</div></div>`;
            });
        });

        html += `</div>`;

        document.getElementById('timeline-content').innerHTML = html;
        document.getElementById('timeline-inner').style.display = '';
        document.getElementById('timeline-loading').style.display = 'none';
        document.getElementById('timeline-empty').style.display = 'none';

        bindBarEvents();
    }

    // ── Tooltip ───────────────────────────────────────────
    const tooltip = document.getElementById('tl-tooltip');

    function bindBarEvents() {
        document.querySelectorAll('.tl-bar').forEach(bar => {
            bar.addEventListener('mouseenter', e => {
                document.getElementById('tt-title').textContent   = bar.dataset.title;
                document.getElementById('tt-service').textContent = bar.dataset.service;
                document.getElementById('tt-obj').textContent     = bar.dataset.obj;
                document.getElementById('tt-periode').textContent = bar.dataset.periode;
                document.getElementById('tt-link').href           = bar.dataset.url;
                tooltip.classList.add('visible');
                positionTooltip(e);
            });
            bar.addEventListener('mousemove', positionTooltip);
            bar.addEventListener('mouseleave', () => tooltip.classList.remove('visible'));
            bar.addEventListener('click', () => { window.location.href = bar.dataset.url; });
        });
    }

    function positionTooltip(e) {
        const x = e.clientX + 12;
        const y = e.clientY + 12;
        tooltip.style.left = Math.min(x, window.innerWidth - 280) + 'px';
        tooltip.style.top  = Math.min(y, window.innerHeight - 180) + 'px';
    }

    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Fetch ─────────────────────────────────────────────
    function fetchAndRender() {
        document.getElementById('timeline-inner').style.display   = 'none';
        document.getElementById('timeline-empty').style.display   = 'none';
        document.getElementById('timeline-loading').style.display = '';

        const params = new URLSearchParams();
        const svc = document.getElementById('filter-service').value;
        const wf  = document.getElementById('filter-wf').value;
        const per = document.getElementById('filter-periode').value;
        if (svc) params.set('service_id', svc);
        if (wf)  params.set('workflow_status', wf);
        if (per) params.set('periode_id', per);

        fetch(`${API_URL}?${params}`, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            currentData = data;
            const total = data.groups.reduce((s, g) => s + g.items.length, 0);
            if (total === 0) {
                document.getElementById('timeline-loading').style.display = 'none';
                document.getElementById('timeline-empty').style.display   = '';
                return;
            }
            render(data, currentZoom);
        })
        .catch(() => {
            document.getElementById('timeline-loading').style.display = 'none';
            document.getElementById('timeline-empty').style.display   = '';
        });
    }

    // ── Zoom buttons ─────────────────────────────────────
    document.querySelectorAll('.tl-zoom-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tl-zoom-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentZoom = btn.dataset.zoom;
            if (currentData) render(currentData, currentZoom);
        });
    });

    // ── Filtres ───────────────────────────────────────────
    ['filter-service', 'filter-wf', 'filter-periode'].forEach(id => {
        document.getElementById(id).addEventListener('change', fetchAndRender);
    });

    // ── Export PNG (html2canvas simplifié via screenshot) ─
    document.getElementById('btn-export-png').addEventListener('click', () => {
        const el = document.getElementById('timeline-inner');
        if (!el) return;
        window.print();
    });

    // ── Init ──────────────────────────────────────────────
    fetchAndRender();
})();
</script>
@endpush
