@extends('layouts.app')

@section('title', 'Export')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('analytics') }}">Analytics</a></li>
    <li class="breadcrumb-item active">Export</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Export des données</h1>
        <p class="page-subtitle">Téléchargez vos activités et rapports en CSV ou PDF</p>
    </div>
</div>

<div class="row g-4">
    {{-- ===== FORMULAIRE ===== --}}
    <div class="col-lg-8">
        <x-card title="Paramètres d'export" icon="bi-sliders">
            <form action="{{ route('export.process') }}" method="POST" id="exportForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Type d'export <span class="required">*</span></label>
                        <select class="form-select" id="exportType" name="type" required>
                            <option value="">Choisir…</option>
                            <option value="activities">Activités</option>
                            <option value="global_stats">Statistiques globales</option>
                            <option value="performance">Rapport de performance</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Format <span class="required">*</span></label>
                        <select class="form-select" name="format" required>
                            <option value="">Choisir…</option>
                            <option value="csv">CSV (Excel / Google Sheets)</option>
                            <option value="json">JSON (API / intégration)</option>
                        </select>
                    </div>

                    {{-- Filtres activités (conditionnels) --}}
                    <div id="activityFilters" class="col-12" style="display:none;">
                        <div style="background:var(--clr-gray-50);border:1px solid var(--border-color);border-radius:var(--border-radius);padding:var(--space-4);">
                            <div class="text-sm fw-semi mb-3"><i class="bi bi-funnel me-2 text-primary"></i>Filtres optionnels</div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Service</label>
                                    <select class="form-select form-select-sm" name="service_id">
                                        <option value="">Tous</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Workflow</label>
                                    <select class="form-select form-select-sm" name="workflow_status">
                                        <option value="">Tous</option>
                                        @foreach(\App\Models\Activity::WORKFLOW_LABELS as $val => $lbl)
                                            <option value="{{ $val }}">{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Statut activité</label>
                                    <select class="form-select form-select-sm" name="status">
                                        <option value="">Tous</option>
                                        <option value="1">Actives</option>
                                        <option value="0">Inactives</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date de début</label>
                                    <input type="date" class="form-control form-control-sm" name="date_from">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date de fin</label>
                                    <input type="date" class="form-control form-control-sm" name="date_to">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info contextuelle --}}
                    <div class="col-12" id="exportInfo">
                        <x-alert type="info" :dismissible="false">
                            Sélectionnez un type d'export pour voir la description.
                        </x-alert>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top:1px solid var(--border-color);">
                    <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('exportForm').reset();document.getElementById('activityFilters').style.display='none';">
                        <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i>Télécharger
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    {{-- ===== EXPORTS RAPIDES ===== --}}
    <div class="col-lg-4">
        <x-card title="Exports rapides" icon="bi-lightning-charge" class="mb-4">
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('export.activities.csv') }}" class="btn btn-ghost btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-spreadsheet text-success"></i>
                    Toutes les activités (CSV)
                </a>
                <a href="{{ route('export.stats.csv') }}" class="btn btn-ghost btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-graph-up text-primary"></i>
                    Statistiques globales (CSV)
                </a>
                <a href="{{ route('export.performance.csv') }}" class="btn btn-ghost btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-speedometer2 text-warning"></i>
                    Rapport performance (CSV)
                </a>
            </div>
        </x-card>

        <x-card title="Exports PDF" icon="bi-file-earmark-pdf" class="mb-4">
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('export.pdf.activities') }}" target="_blank"
                   class="btn btn-ghost btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                    Rapport activités (PDF)
                </a>
                <a href="{{ route('export.pdf.performance') }}" target="_blank"
                   class="btn btn-ghost btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                    Rapport performance (PDF)
                </a>
            </div>
        </x-card>

        <x-card title="Aide" icon="bi-question-circle">
            <div class="d-flex flex-column gap-3">
                <div>
                    <div class="text-xs fw-semi text-muted mb-1">FORMATS</div>
                    <div class="text-sm"><i class="bi bi-file-earmark-spreadsheet text-success me-2"></i>CSV — Excel / Google Sheets</div>
                    <div class="text-sm mt-1"><i class="bi bi-file-earmark-code text-primary me-2"></i>JSON — Intégration API</div>
                </div>
                <div>
                    <div class="text-xs fw-semi text-muted mb-1">COLONNES EXPORT ACTIVITÉS</div>
                    <div class="text-xs text-muted">ID, Libellé, Service, Objectif, Sous-Objectif, Période, Indicateur, Cible, Budget, Financement, Structure, Statut, <strong>Workflow</strong>, Soumis le, Validé le, Créé le</div>
                </div>
            </div>
        </x-card>
    </div>
</div>

@endsection

@push('scripts')
<script>
const EXPORT_INFO = {
    activities:   'Liste détaillée de toutes les activités avec leurs informations workflow.',
    global_stats: 'Chiffres clés du système : objectifs, activités, budget, services.',
    performance:  'Évolution mensuelle et performance par service sur les 6 derniers mois.',
};

document.getElementById('exportType').addEventListener('change', function () {
    const filters = document.getElementById('activityFilters');
    const info    = document.getElementById('exportInfo');
    filters.style.display = this.value === 'activities' ? 'block' : 'none';
    info.innerHTML = this.value
        ? `<div class="alert alert-info mb-0"><i class="bi bi-info-circle me-2"></i>${EXPORT_INFO[this.value]}</div>`
        : `<div class="alert alert-info mb-0"><i class="bi bi-info-circle me-2"></i>Sélectionnez un type d'export.</div>`;
});

</script>
@endpush
