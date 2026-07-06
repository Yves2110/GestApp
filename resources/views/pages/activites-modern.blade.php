@extends('layouts.app')

@section('title', 'Gestion des Activités - GestApp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-1">
            <i class="bi bi-activity text-university-primary me-2"></i>
            Gestion des Activités
        </h2>
        <p class="text-muted mb-0">Suivi et gestion des activités universitaires</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
        <i class="bi bi-plus-circle me-2"></i>
        Nouvelle Activité
    </button>
</div>

<!-- Flash Messages -->
@if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session()->get('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

<!-- Activities Table - Optimized Space -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center g-3">
            <div class="col-md-6 col-lg-7">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Liste des Activités
                </h5>
            </div>
            <div class="col-md-6 col-lg-5 text-md-end">
                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                    <button class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0" onclick="window.print()">
                        <i class="bi bi-printer me-1 d-none d-sm-inline"></i>
                        <span class="d-sm-none d-inline"><i class="bi bi-printer"></i></span>
                        <span class="d-none d-sm-inline">Imprimer</span>
                    </button>
                    <button class="btn btn-outline-success btn-sm flex-fill flex-md-grow-0" onclick="exportToExcel()">
                        <i class="bi bi-file-earmark-excel me-1 d-none d-sm-inline"></i>
                        <span class="d-sm-none d-inline"><i class="bi bi-file-earmark-excel"></i></span>
                        <span class="d-none d-sm-inline">Exporter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-2 p-md-3">
        @if($activities->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="activitiesTable">
                    <thead>
                        <tr>
                            <th class="d-none d-md-table-cell"><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-tag me-1"></i>Libellé</th>
                            <th class="d-none d-lg-table-cell"><i class="bi bi-building me-1"></i>Service</th>
                            <th class="d-none d-xl-table-cell"><i class="bi bi-bullseye me-1"></i>Objectif</th>
                            <th class="d-none d-xl-table-cell"><i class="bi bi-diagram-3 me-1"></i>Sous-Objectif</th>
                            <th class="d-none d-lg-table-cell"><i class="bi bi-calendar3 me-1"></i>Période</th>
                            <th><i class="bi bi-currency-euro me-1"></i>Coût</th>
                            <th class="d-none d-md-table-cell"><i class="bi bi-flag me-1"></i>Statut</th>
                            <th><i class="bi bi-gear me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td class="d-none d-md-table-cell"><span class="badge bg-light text-dark small">#{{ $activity->id }}</span></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="text-truncate" style="max-width: 150px;" title="{{ $activity->label }}">
                                            {{ Str::limit($activity->label, 30) }}
                                        </strong>
                                        @if($activity->commentary)
                                            <small class="text-muted d-none d-lg-block">{{ Str::limit($activity->commentary, 50) }}</small>
                                        @endif
                                        <small class="text-muted d-lg-none d-block">
                                            {{ $activity->service->service ?? 'N/A' }}
                                        </small>
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <span class="badge bg-university-secondary text-white small">
                                        {{ Str::limit($activity->service->service ?? 'N/A', 15) }}
                                    </span>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    <small class="text-truncate d-block" style="max-width: 120px;" title="{{ $activity->objective->label ?? 'N/A' }}">
                                        {{ Str::limit($activity->objective->label ?? 'N/A', 20) }}
                                    </small>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    <small class="text-truncate d-block" style="max-width: 120px;" title="{{ $activity->under_objective->label ?? 'N/A' }}">
                                        {{ Str::limit($activity->under_objective->label ?? 'N/A', 20) }}
                                    </small>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <small class="text-truncate d-block" style="max-width: 100px;" title="{{ $activity->periode->label ?? 'N/A' }}">
                                        {{ Str::limit($activity->periode->label ?? 'N/A', 15) }}
                                    </small>
                                </td>
                                <td>
                                    <strong class="small">{{ number_format($activity->price, 0, ',', ' ') }} €</strong>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if($activity->status)
                                        <span class="badge bg-success small">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning small">
                                            <i class="bi bi-clock me-1"></i>En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                data-bs-toggle="tooltip" title="Voir les détails"
                                                onclick="viewActivity({{ $activity->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-warning btn-sm d-none d-lg-table-cell" 
                                                data-bs-toggle="tooltip" title="Modifier"
                                                onclick="editActivity({{ $activity->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-none d-xl-table-cell" 
                                                data-bs-toggle="tooltip" title="Supprimer"
                                                onclick="deleteActivity({{ $activity->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <!-- Mobile action dropdown -->
                                        <div class="dropdown d-lg-none d-xl-none">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item small" href="#" onclick="editActivity({{ $activity->id }})">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item small text-danger" href="#" onclick="deleteActivity({{ $activity->id }})">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Affichage de {{ $activities->firstItem() }} à {{ $activities->lastItem() }} 
                    sur {{ $activities->total() }} activités
                </small>
                {{ $activities->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">Aucune activité trouvée</h5>
                <p class="text-muted">Commencez par créer votre première activité</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
                    <i class="bi bi-plus-circle me-2"></i>
                    Créer une activité
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Activity Modal -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle text-university-primary me-2"></i>
                    Ajouter une Activité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('ActivitiesStore') }}" method="post">
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
                            <select class="form-select" name="service_id" required>
                                <option value="" selected disabled>Choisir un service</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->service }}</option>
                                @endforeach
                            </select>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Enregistrer l'activité
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

// View activity details
function viewActivity(id) {
    // Implementation for viewing activity details
    console.log('View activity:', id);
}

// Edit activity
function editActivity(id) {
    // Implementation for editing activity
    console.log('Edit activity:', id);
}

// Delete activity
function deleteActivity(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')) {
        // Implementation for deleting activity
        console.log('Delete activity:', id);
    }
}

// Export to Excel
function exportToExcel() {
    // Implementation for Excel export
    console.log('Export to Excel');
}

// Initialize DataTable for better table management
$(document).ready(function() {
    $('#activitiesTable').DataTable({
        "language": {
            "search": "Rechercher:",
            "lengthMenu": "Afficher _MENU_ entrées",
            "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "paginate": {
                "first": "Premier",
                "last": "Dernier",
                "next": "Suivant",
                "previous": "Précédent"
            }
        },
        "pageLength": 10,
        "responsive": true
    });
});
</script>
@endsection
@endsection
