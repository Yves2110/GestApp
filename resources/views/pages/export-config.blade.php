@extends('layouts.app')

@section('title', 'Configuration Export - GestApp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-1">
            <i class="bi bi-download text-university-primary me-2"></i>
            Configuration des Exports
        </h2>
        <p class="text-muted mb-0">Exportez vos données en différents formats</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear text-university-secondary me-2"></i>
                    Paramètres d'Export
                </h5>
            </div>
            <form action="{{ route('export.process') }}" method="POST" id="exportForm">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Type d'export -->
                        <div class="col-md-6">
                            <label for="type" class="form-label">
                                <i class="bi bi-file-earmark me-1"></i>Type d'export *
                            </label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Choisir le type d'export</option>
                                <option value="activities">Activités</option>
                                <option value="global_stats">Statistiques globales</option>
                                <option value="performance">Rapport de performance</option>
                            </select>
                        </div>

                        <!-- Format -->
                        <div class="col-md-6">
                            <label for="format" class="form-label">
                                <i class="bi bi-file-earmark-code me-1"></i>Format *
                            </label>
                            <select class="form-select" id="format" name="format" required>
                                <option value="">Choisir le format</option>
                                <option value="csv">CSV (Excel)</option>
                                <option value="json">JSON (Données brutes)</option>
                            </select>
                        </div>

                        <!-- Filtres pour les activités -->
                        <div id="activityFilters" class="col-12" style="display: none;">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="mb-3">
                                    <i class="bi bi-funnel me-2"></i>
                                    Filtres pour les activités
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="service_id" class="form-label">Service</label>
                                        <select class="form-select" name="service_id">
                                            <option value="">Tous les services</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->service }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Statut</label>
                                        <select class="form-select" name="status">
                                            <option value="">Tous les statuts</option>
                                            <option value="1">Actives</option>
                                            <option value="0">En attente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="date_range" class="form-label">Période</label>
                                        <select class="form-select" id="date_range" onchange="toggleDateFields()">
                                            <option value="">Toutes les dates</option>
                                            <option value="custom">Personnalisée</option>
                                        </select>
                                    </div>
                                    <div id="customDateFields" class="col-12" style="display: none;">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="date_from" class="form-label">Date de début</label>
                                                <input type="date" class="form-control" name="date_from">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="date_to" class="form-label">Date de fin</label>
                                                <input type="date" class="form-control" name="date_to">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu des données -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Aperçu des données :</strong>
                                <span id="dataPreview">Sélectionnez un type d'export pour voir l'aperçu</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" onclick="previewData()">
                            <i class="bi bi-eye me-1"></i>
                            Aperçu
                        </button>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-download me-1"></i>
                                Exporter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Exports -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-lightning text-university-warning me-2"></i>
                    Exports Rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('export.activities.csv') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                        Toutes les activités (CSV)
                    </a>
                    <a href="{{ route('export.stats.csv') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-graph-up me-1"></i>
                        Statistiques globales (CSV)
                    </a>
                    <a href="{{ route('export.performance.csv') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Rapport performance (CSV)
                    </a>
                </div>
            </div>
        </div>

        <!-- Export History -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-clock-history text-university-secondary me-2"></i>
                    Historique d'Export
                </h6>
            </div>
            <div class="card-body">
                <div id="exportHistory" class="small">
                    <p class="text-muted mb-0">Aucun export récent</p>
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-question-circle text-university-info me-2"></i>
                    Aide
                </h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <p class="mb-2"><strong>Formats disponibles :</strong></p>
                    <ul class="list-unstyled mb-3">
                        <li><i class="bi bi-file-earmark-spreadsheet text-success me-1"></i> CSV : Pour Excel/Sheets</li>
                        <li><i class="bi bi-file-earmark-code text-primary me-1"></i> JSON : Pour intégration API</li>
                    </ul>
                    <p class="mb-2"><strong>Types d'export :</strong></p>
                    <ul class="list-unstyled">
                        <li>• Activités : Liste détaillée des activités</li>
                        <li>• Statistiques globales : Chiffres clés du système</li>
                        <li>• Performance : Évolution et tendances</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye text-university-primary me-2"></i>
                    Aperçu des Données
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement de l'aperçu...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="proceedWithExport()">
                    <i class="bi bi-download me-1"></i>
                    Procéder à l'export
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle filters based on export type
document.getElementById('type').addEventListener('change', function() {
    const activityFilters = document.getElementById('activityFilters');
    const dataPreview = document.getElementById('dataPreview');
    
    if (this.value === 'activities') {
        activityFilters.style.display = 'block';
        dataPreview.textContent = 'Export de toutes les activités avec filtres optionnels';
    } else if (this.value === 'global_stats') {
        activityFilters.style.display = 'none';
        dataPreview.textContent = 'Export des statistiques globales du système';
    } else if (this.value === 'performance') {
        activityFilters.style.display = 'none';
        dataPreview.textContent = 'Export du rapport de performance (6 derniers mois)';
    } else {
        activityFilters.style.display = 'none';
        dataPreview.textContent = 'Sélectionnez un type d\'export pour voir l\'aperçu';
    }
});

// Toggle custom date fields
function toggleDateFields() {
    const dateRange = document.getElementById('date_range').value;
    const customDateFields = document.getElementById('customDateFields');
    
    customDateFields.style.display = dateRange === 'custom' ? 'block' : 'none';
}

// Preview data
function previewData() {
    const type = document.getElementById('type').value;
    const format = document.getElementById('format').value;
    
    if (!type || !format) {
        alert('Veuillez sélectionner un type et un format d\'export');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    // Load preview data
    const formData = new FormData(document.getElementById('exportForm'));
    const params = new URLSearchParams(formData);
    
    fetch('{{ route("api.export.data") }}?' + params.toString())
        .then(response => response.json())
        .then(data => {
            displayPreviewData(data, format);
        })
        .catch(error => {
            document.getElementById('previewContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Erreur lors du chargement de l'aperçu: ${error.message}
                </div>
            `;
        });
}

// Display preview data
function displayPreviewData(data, format) {
    let content = '';
    
    if (format === 'json') {
        content = `
            <div class="mb-3">
                <h6>Données JSON (${data.data.generated_at})</h6>
                <small class="text-muted">Fichier: ${data.filename}</small>
            </div>
            <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">${JSON.stringify(data.data, null, 2)}</pre>
        `;
    } else {
        // CSV preview
        content = `
            <div class="mb-3">
                <h6>Aperçu CSV (${data.data.generated_at})</h6>
                <small class="text-muted">Fichier: ${data.filename.replace('.json', '.csv')}</small>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Statistiques</th>
                            <th>Valeurs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Objectifs totaux</td><td>${data.data.stats?.total_objectives || 'N/A'}</td></tr>
                        <tr><td>Activités totales</td><td>${data.data.stats?.total_activities || 'N/A'}</td></tr>
                        <tr><td>Budget total</td><td>${data.data.stats?.total_budget ? data.data.stats.total_budget + ' €' : 'N/A'}</td></tr>
                        <tr><td>Services</td><td>${data.data.stats?.total_services || 'N/A'}</td></tr>
                    </tbody>
                </table>
            </div>
        `;
    }
    
    document.getElementById('previewContent').innerHTML = content;
}

// Proceed with export
function proceedWithExport() {
    document.getElementById('exportForm').submit();
}

// Reset form
function resetForm() {
    document.getElementById('exportForm').reset();
    document.getElementById('activityFilters').style.display = 'none';
    document.getElementById('customDateFields').style.display = 'none';
    document.getElementById('dataPreview').textContent = 'Sélectionnez un type d\'export pour voir l\'aperçu';
}

// Update export history (simulation)
function updateExportHistory() {
    const history = [
        { type: 'Activités', format: 'CSV', date: '06/07/2026 14:30', size: '2.4 MB' },
        { type: 'Statistiques', format: 'CSV', date: '06/07/2026 13:15', size: '156 KB' },
        { type: 'Performance', format: 'CSV', date: '05/07/2026 16:45', size: '892 KB' },
    ];
    
    let historyHtml = '<div class="list-group list-group-flush">';
    history.forEach(item => {
        historyHtml += `
            <div class="list-group-item px-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted">${item.type} (${item.format})</small>
                        <br>
                        <small>${item.date} • ${item.size}</small>
                    </div>
                    <i class="bi bi-check-circle text-success"></i>
                </div>
            </div>
        `;
    });
    historyHtml += '</div>';
    
    document.getElementById('exportHistory').innerHTML = historyHtml;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateExportHistory();
});
</script>
@endpush
