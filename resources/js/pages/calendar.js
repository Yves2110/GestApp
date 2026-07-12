import { Calendar } from '@fullcalendar/core';
import dayGridPlugin     from '@fullcalendar/daygrid';
import timeGridPlugin    from '@fullcalendar/timegrid';
import listPlugin        from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('calendar');
    if (!el) return;

    const API_URL = el.dataset.apiUrl;
    const CSRF    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function buildParams() {
        const params = new URLSearchParams();
        const svc = document.getElementById('filter-service')?.value;
        const wf  = document.getElementById('filter-wf')?.value;
        if (svc) params.set('service_id', svc);
        if (wf)  params.set('workflow_status', wf);
        return params.toString();
    }

    const calendar = new Calendar(el, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listMonth',
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week:  'Semaine',
            list:  'Agenda',
        },
        height: 'auto',
        events: function(info, successCb, failureCb) {
            fetch(`${API_URL}?${buildParams()}`, {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(successCb)
            .catch(failureCb);
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            openDetail(info.event);
        },
        eventMouseEnter: function(info) { info.el.style.opacity = '.85'; },
        eventMouseLeave: function(info) { info.el.style.opacity = '1'; },
        noEventsContent: 'Aucune activité à afficher',
    });

    calendar.render();

    window.__gestappCalendar = calendar;

    function openDetail(event) {
        const p = event.extendedProps;
        const wfLabels = { draft: 'Brouillon', pending: 'En attente', validated: 'Validé', rejected: 'Rejeté' };
        const wfColors = { draft: '#6c757d', pending: '#fd7e14', validated: '#198754', rejected: '#dc3545' };
        const wf = p.workflow_status || 'draft';

        document.getElementById('detail-content').innerHTML = `
            <h6 class="fw-semi mb-3" style="line-height:1.4;">${event.title}</h6>
            <span class="detail-wf-badge mb-3"
                  style="background:${wfColors[wf]}1a;color:${wfColors[wf]};border:1px solid ${wfColors[wf]}40;">
                ${wfLabels[wf] ?? wf}
            </span>
            <div class="d-flex flex-column gap-3 mt-3">
                <div>
                    <div class="text-xs text-muted fw-semi text-uppercase mb-1">Service</div>
                    <div class="text-sm fw-medium">${p.service}</div>
                </div>
                <div>
                    <div class="text-xs text-muted fw-semi text-uppercase mb-1">Objectif</div>
                    <div class="text-sm">${p.objective}</div>
                </div>
                <div>
                    <div class="text-xs text-muted fw-semi text-uppercase mb-1">Période</div>
                    <div class="text-sm">${p.periode}</div>
                </div>
            </div>
            <hr style="border-color:var(--border-color);margin:1.25rem 0;">
            <a href="${p.url}" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-eye me-1"></i> Voir le détail
            </a>
        `;

        document.getElementById('detail-panel').classList.add('open');
        document.getElementById('detail-panel-overlay').classList.add('open');
    }

    function closeDetail() {
        document.getElementById('detail-panel').classList.remove('open');
        document.getElementById('detail-panel-overlay').classList.remove('open');
    }

    document.getElementById('close-panel')?.addEventListener('click', closeDetail);
    document.getElementById('detail-panel-overlay')?.addEventListener('click', closeDetail);

    ['filter-service', 'filter-wf'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', () => {
            calendar.refetchEvents();
        });
    });
});
