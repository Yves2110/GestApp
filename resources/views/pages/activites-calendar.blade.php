@extends('layouts.app')

@section('title', 'Calendrier des activités')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('Activites') }}">Activités</a></li>
    <li class="breadcrumb-item active">Calendrier</li>
@endsection

@push('styles')
<style>
/* ── Calendrier container ─────────────────────────────────── */
#calendar-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}

/* FullCalendar overrides */
.fc {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
}
.fc-toolbar-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semi);
    color: var(--text-primary);
}
.fc-button-primary {
    background-color: var(--clr-primary) !important;
    border-color: var(--clr-primary) !important;
    font-size: var(--font-size-sm) !important;
    border-radius: var(--border-radius) !important;
    padding: .35rem .75rem !important;
}
.fc-button-primary:hover {
    background-color: var(--clr-primary-dark, #1a3a8a) !important;
    border-color: var(--clr-primary-dark, #1a3a8a) !important;
}
.fc-button-primary:not(:disabled).fc-button-active,
.fc-button-primary:not(:disabled):active {
    background-color: var(--clr-primary-dark, #1a3a8a) !important;
    border-color: var(--clr-primary-dark, #1a3a8a) !important;
}
.fc-daygrid-day-number,
.fc-col-header-cell-cushion {
    color: var(--text-primary);
    text-decoration: none;
}
.fc-day-today {
    background-color: var(--clr-primary-light, #eef2ff) !important;
}
.fc-event {
    cursor: pointer;
    border-radius: 4px !important;
    font-size: .75rem !important;
    padding: 1px 4px !important;
}
.fc-event-title { font-weight: 500; }
.fc-daygrid-event-dot { display: none; }

/* ── Panneau de détail ─────────────────────────────────────── */
#detail-panel {
    position: fixed;
    top: 0;
    right: -380px;
    width: 360px;
    height: 100%;
    background: var(--bg-card);
    border-left: 1px solid var(--border-color);
    box-shadow: var(--shadow-lg);
    z-index: 1050;
    transition: right .3s ease;
    overflow-y: auto;
    padding: var(--space-6);
}
#detail-panel.open { right: 0; }
#detail-panel-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.3);
    z-index: 1049;
}
#detail-panel-overlay.open { display: block; }
.detail-wf-badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .75rem;
    font-weight: 600;
    padding: .25rem .6rem;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .04em;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Calendrier des activités</h1>
        <p class="page-subtitle">Vue mensuelle, hebdomadaire et agenda</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('Activites') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-list-ul"></i>
            <span class="hide-mobile">Vue liste</span>
        </a>
        <a href="{{ route('activites.kanban') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-kanban"></i>
            <span class="hide-mobile">Kanban</span>
        </a>
    </div>
</div>

{{-- Filtres --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form id="calendar-filters" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-xs text-muted fw-semi text-uppercase">Service</label>
                <select class="form-select form-select-sm" name="service_id" id="filter-service">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-xs text-muted fw-semi text-uppercase">Statut workflow</label>
                <select class="form-select form-select-sm" name="workflow_status" id="filter-wf">
                    <option value="">Tous</option>
                    <option value="draft">Brouillon</option>
                    <option value="pending">En attente</option>
                    <option value="validated">Validé</option>
                    <option value="rejected">Rejeté</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-1 text-xs">
                        <span style="width:12px;height:12px;border-radius:3px;background:#6c757d;display:inline-block;"></span> Brouillon
                    </div>
                    <div class="d-flex align-items-center gap-1 text-xs">
                        <span style="width:12px;height:12px;border-radius:3px;background:#fd7e14;display:inline-block;"></span> En attente
                    </div>
                    <div class="d-flex align-items-center gap-1 text-xs">
                        <span style="width:12px;height:12px;border-radius:3px;background:#198754;display:inline-block;"></span> Validé
                    </div>
                    <div class="d-flex align-items-center gap-1 text-xs">
                        <span style="width:12px;height:12px;border-radius:3px;background:#dc3545;display:inline-block;"></span> Rejeté
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Calendrier --}}
<div id="calendar-wrapper" class="p-3">
    <div id="calendar" data-api-url="{{ route('api.calendar.events') }}"></div>
</div>

{{-- Overlay --}}
<div id="detail-panel-overlay"></div>

{{-- Panneau de détail --}}
<div id="detail-panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-semi mb-0">Détail de l'activité</h6>
        <button class="btn btn-ghost btn-icon btn-sm" id="close-panel">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div id="detail-content">
        <x-skeleton lines="5" />
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/calendar.js')
@endpush
