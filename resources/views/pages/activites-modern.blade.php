@extends('layouts.app')

@section('title', 'Activités')

@section('breadcrumb')
    <li class="breadcrumb-item active">Activités</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Activités</h1>
        <p class="page-subtitle">{{ $activities->total() }} activité{{ $activities->total() > 1 ? 's' : '' }} au total</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('activites.kanban') }}" class="btn btn-ghost btn-sm" title="Vue Kanban">
            <i class="bi bi-kanban"></i>
            <span class="hide-mobile">Kanban</span>
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
            <i class="bi bi-plus-lg"></i>
            Nouvelle activité
        </button>
    </div>
</div>

{{-- ====== FILTRES ====== --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('Activites') }}" class="row g-3 align-items-end">
            {{-- Recherche --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <label class="form-label">Rechercher</label>
                <div class="search-input-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Libellé…"
                           value="{{ $filters['search'] ?? '' }}">
                </div>
            </div>

            {{-- Service (admin+ uniquement) --}}
            @if(Auth::user()->role_id < 4)
            <div class="col-12 col-sm-6 col-lg-2">
                <label class="form-label">Service</label>
                <select name="service_id" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}"
                            {{ ($filters['service_id'] ?? '') == $s->id ? 'selected' : '' }}>
                            {{ $s->label }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Workflow status --}}
            <div class="col-12 col-sm-6 col-lg-2">
                <label class="form-label">Statut workflow</label>
                <select name="workflow_status" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    @foreach(\App\Models\Activity::WORKFLOW_LABELS as $val => $lbl)
                        <option value="{{ $val }}"
                            {{ ($filters['workflow_status'] ?? '') === $val ? 'selected' : '' }}>
                            {{ $lbl }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Période --}}
            <div class="col-12 col-sm-6 col-lg-2">
                <label class="form-label">Période</label>
                <select name="periode_id" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    @foreach($trimestres as $t)
                        <option value="{{ $t->id }}"
                            {{ ($filters['periode_id'] ?? '') == $t->id ? 'selected' : '' }}>
                            {{ $t->label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Actions --}}
            <div class="col-12 col-lg-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel"></i> Filtrer
                </button>
                @if(array_filter($filters ?? []))
                    <a href="{{ route('Activites') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg"></i> Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- ====== TABLE ====== --}}
<div class="table-wrapper">
    <div class="table-toolbar">
        <h5 class="table-toolbar-title">
            <i class="bi bi-list-ul me-2 text-muted"></i>Liste des activités
        </h5>
        <div class="d-flex gap-2">
            <button class="btn btn-ghost btn-sm no-print" onclick="window.print()" title="Imprimer">
                <i class="bi bi-printer"></i>
                <span class="hide-mobile">Imprimer</span>
            </button>
            @if(Auth::user()->role_id <= 3)
            <a href="{{ route('export.config') }}" class="btn btn-ghost btn-sm" title="Exporter">
                <i class="bi bi-download"></i>
                <span class="hide-mobile">Exporter</span>
            </a>
            @endif
        </div>
    </div>

    @if($activities->count() > 0)
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-id">#</th>
                    <th>Libellé</th>
                    <th class="hide-mobile">Service</th>
                    <th class="hide-mobile">Période</th>
                    <th>Statut</th>
                    <th>Workflow</th>
                    <th class="hide-mobile" style="text-align:right;">Budget</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                @php
                    $wfClass = match($activity->workflow_status) {
                        'draft'     => 'badge-status--draft',
                        'pending'   => 'badge-status--pending',
                        'validated' => 'badge-status--active',
                        'rejected'  => 'badge-status--rejected',
                        default     => 'badge-secondary',
                    };
                @endphp
                <tr>
                    <td class="col-id">{{ $activity->id }}</td>
                    <td>
                        <div class="fw-medium text-sm">
                            <a href="{{ route('activites.show', $activity->id) }}" class="text-primary">
                                {{ \Illuminate\Support\Str::limit($activity->label, 55) }}
                            </a>
                        </div>
                        @if($activity->objective)
                            <div class="text-xs text-muted hide-mobile">{{ \Illuminate\Support\Str::limit($activity->objective->label, 40) }}</div>
                        @endif
                    </td>
                    <td class="hide-mobile">
                        <span class="badge badge-secondary">{{ $activity->service->label ?? '—' }}</span>
                    </td>
                    <td class="hide-mobile">
                        <span class="text-sm text-muted">{{ $activity->periode->label ?? '—' }}</span>
                    </td>
                    <td>
                        @if($activity->status)
                            <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge badge-warning"><i class="bi bi-hourglass me-1"></i>Inactif</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-status {{ $wfClass }}">{{ $activity->workflowLabel }}</span>
                    </td>
                    <td class="hide-mobile" style="text-align:right;">
                        <span class="text-sm fw-medium">{{ $activity->price ? number_format($activity->price, 0, ',', ' ') . ' FCFA' : '—' }}</span>
                    </td>
                    <td class="col-actions">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('activites.show', $activity->id) }}"
                               class="btn btn-ghost btn-icon btn-sm" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('activites.edit', $activity->id) }}"
                               class="btn btn-ghost btn-icon btn-sm" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-ghost btn-icon btn-sm text-danger"
                                    title="Supprimer"
                                    onclick="openDeleteModal({{ $activity->id }}, @js($activity->label))">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <form id="delete-activity-{{ $activity->id }}"
                              action="{{ route('activites.destroy', $activity->id) }}"
                              method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        <span class="pagination-info">
            Affichage de <strong>{{ $activities->firstItem() }}</strong>
            à <strong>{{ $activities->lastItem() }}</strong>
            sur <strong>{{ $activities->total() }}</strong> activités
        </span>
        {{ $activities->links('vendor.pagination.custom') }}
    </div>

    @else
    <x-empty-state
        icon="bi-lightning-charge"
        title="Aucune activité trouvée"
        :message="array_filter($filters ?? []) ? 'Essayez de modifier vos filtres.' : 'Créez votre première activité.'"
    >
        <x-slot:action>
            <button type="button" class="btn btn-primary btn-sm"
                    data-bs-toggle="modal" data-bs-target="#activityModal">
                <i class="bi bi-plus-lg"></i> Nouvelle activité
            </button>
        </x-slot:action>
    </x-empty-state>
    @endif
</div>

{{-- ====== MODAL CRÉATION ACTIVITÉ ====== --}}
<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel">
                    <i class="bi bi-plus-lg me-2"></i>Nouvelle activité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('ActivitiesStore') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="objective_id" class="form-label">
                                <i class="bi bi-bullseye me-1"></i>Objectif Global *
                            </label>
                            <select id="inputState" class="form-select" name="objective_id" required>
                                <option value="" selected disabled>Choisir un objectif</option>
                                @foreach ($objectives as $objective)
                                    <option value="{{ $objective->id }}">{{ $objective->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="under_objective_id" class="form-label">
                                <i class="bi bi-diagram-3 me-1"></i>Sous-Objectif *
                            </label>
                            <select id="inputState" class="form-select" name="under_objective_id" required>
                                <option value="" selected disabled>Choisir un sous-objectif</option>
                                @foreach ($underobjectives as $underobjective)
                                    <option value="{{ $underobjective->id }}">{{ $underobjective->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="service_id" class="form-label">
                                <i class="bi bi-building me-1"></i>Service *
                            </label>
                            @if (auth()->check() && (int) auth()->user()->role_id === 4)
                                {{-- Utilisateur Service : verrouillé sur son propre service --}}
                                <select class="form-select" disabled>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" selected>{{ $service->label }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="service_id" value="{{ auth()->user()->service_id }}">
                                <span class="form-text">Vous ne pouvez gérer que les activités de votre service.</span>
                            @else
                                <select class="form-select" name="service_id" required>
                                    <option value="" selected disabled>Choisir un service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->label }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="periode_id" class="form-label">
                                <i class="bi bi-calendar3 me-1"></i>Période *
                            </label>
                            <select class="form-select" name="periode_id" required>
                                <option value="" selected disabled>Choisir une période</option>
                                @foreach ($trimestres as $trimestre)
                                    <option value="{{ $trimestre->id }}">{{ $trimestre->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="label" class="form-label">
                                <i class="bi bi-tag me-1"></i>Libellé de l'activité *
                            </label>
                            <input type="text" class="form-control" name="label" required 
                                   placeholder="Entrez le libellé de l'activité">
                        </div>
                        <div class="col-md-6">
                            <label for="indicator" class="form-label">
                                <i class="bi bi-speedometer2 me-1"></i>Indicateur *
                            </label>
                            <input type="text" class="form-control" name="indicator" required 
                                   placeholder="Indicateur de performance">
                        </div>
                        <div class="col-md-6">
                            <label for="target" class="form-label">
                                <i class="bi bi-bullseye me-1"></i>Cible *
                            </label>
                            <input type="text" class="form-control" name="target" required 
                                   placeholder="Objectif à atteindre">
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">
                                <i class="bi bi-currency-euro me-1"></i>Coût (€) *
                            </label>
                            <input type="number" class="form-control" name="price" required 
                                   min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label for="source_of_funding" class="form-label">
                                <i class="bi bi-wallet2 me-1"></i>Source de financement *
                            </label>
                            <input type="text" class="form-control" name="source_of_funding" required 
                                   placeholder="Source de financement">
                        </div>
                        <div class="col-12">
                            <label for="structure" class="form-label">
                                <i class="bi bi-building me-1"></i>Structure responsable *
                            </label>
                            <input type="text" class="form-control" name="structure" required 
                                   placeholder="Structure responsable">
                        </div>
                        <div class="col-12">
                            <label for="commentary" class="form-label">
                                <i class="bi bi-chat-text me-1"></i>Commentaires
                            </label>
                            <textarea class="form-control" name="commentary" rows="3" 
                                      placeholder="Commentaires additionnels..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====== MODAL SUPPRESSION ====== --}}
<div class="modal fade modal-confirm" id="deleteActivityModal" tabindex="-1"
     aria-labelledby="deleteActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteActivityModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2 text-sm">Vous allez supprimer définitivement :</p>
                <p class="fw-semi text-danger mb-3" id="deleteActivityLabel"></p>
                <p class="text-muted text-sm mb-3">Saisissez <code>SUPPRIMER</code> pour confirmer.</p>
                <input type="text" class="form-control" id="deleteConfirmInput"
                       placeholder="SUPPRIMER" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn" disabled>
                    <i class="bi bi-trash me-1"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let _deleteId = null;

function openDeleteModal(id, label) {
    _deleteId = id;
    document.getElementById('deleteActivityLabel').textContent = label || ('Activité #' + id);
    const inp = document.getElementById('deleteConfirmInput');
    const btn = document.getElementById('confirmDeleteBtn');
    inp.value = '';
    btn.disabled = true;
    new bootstrap.Modal(document.getElementById('deleteActivityModal')).show();
}

document.addEventListener('DOMContentLoaded', () => {
    const inp = document.getElementById('deleteConfirmInput');
    const btn = document.getElementById('confirmDeleteBtn');
    if (!inp) return;
    inp.addEventListener('input', () => {
        btn.disabled = inp.value.trim().toUpperCase() !== 'SUPPRIMER';
    });
    btn.addEventListener('click', () => {
        if (_deleteId === null) return;
        const form = document.getElementById('delete-activity-' + _deleteId);
        if (form) form.submit();
    });
});
</script>
@endpush
@endsection
