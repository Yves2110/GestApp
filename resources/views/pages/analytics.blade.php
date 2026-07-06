@extends('layouts.app')

@section('title', 'Dashboard Analytique - GestApp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-1">
            <i class="bi bi-graph-up text-university-primary me-2"></i>
            Dashboard Analytique
        </h2>
        <p class="text-muted mb-0">Statistiques et analyses des activités universitaires</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success" onclick="exportAnalytics()">
            <i class="bi bi-file-earmark-excel me-1"></i>
            Exporter Excel
        </button>
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>
            Imprimer
        </button>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card university-stats h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Objectifs Globaux</h6>
                        <h3 class="h2 mb-0">{{ $stats['total_objectives'] }}</h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i>
                            {{ round(($stats['total_under_objectives'] / max($stats['total_objectives'], 1)) * 100, 1) }}% de sous-objectifs
                        </small>
                    </div>
                    <div class="text-university-primary">
                        <i class="bi bi-bullseye" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card university-stats h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Activités Totales</h6>
                        <h3 class="h2 mb-0">{{ $stats['total_activities'] }}</h3>
                        <small class="text-info">
                            <i class="bi bi-activity"></i>
                            {{ $stats['active_activities'] }} actives
                        </small>
                    </div>
                    <div class="text-university-success">
                        <i class="bi bi-activity" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card university-stats h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Budget Total</h6>
                        <h3 class="h2 mb-0">{{ number_format($stats['total_budget'], 0, '', ' ') }} €</h3>
                        <small class="text-warning">
                            <i class="bi bi-currency-euro"></i>
                            {{ number_format($stats['total_budget'] / max($stats['total_activities'], 1), 0, '', ' ') }} €/activité
                        </small>
                    </div>
                    <div class="text-university-warning">
                        <i class="bi bi-currency-euro" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card university-stats h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Services</h6>
                        <h3 class="h2 mb-0">{{ $stats['total_services'] }}</h3>
                        <small class="text-secondary">
                            <i class="bi bi-building"></i>
                            {{ $stats['total_users'] }} utilisateurs
                        </small>
                    </div>
                    <div class="text-university-secondary">
                        <i class="bi bi-building" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Activities by Service Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart text-university-primary me-2"></i>
                    Répartition des Activités par Service
                </h5>
            </div>
            <div class="card-body">
                <canvas id="activitiesByServiceChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart text-university-primary me-2"></i>
                    Statut des Activités
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        Taux de complétion: 
                        <strong class="text-success">
                            {{ round(($statusDistribution['active'] / max(array_sum($statusDistribution), 1)) * 100, 1) }}%
                        </strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Budget and Evolution Charts -->
<div class="row g-4 mb-4">
    <!-- Budget by Service -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cash-stack text-university-success me-2"></i>
                    Budget par Service
                </h5>
            </div>
            <div class="card-body">
                <canvas id="budgetChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Evolution -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up-arrow text-university-info me-2"></i>
                    Évolution Mensuelle (12 mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="evolutionChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Services and Objectives -->
<div class="row g-4 mb-4">
    <!-- Top Services -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-trophy text-university-warning me-2"></i>
                    Top 5 Services les Plus Actifs
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Activités</th>
                                <th>Budget</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topServices as $service)
                                <tr>
                                    <td>
                                        <strong>{{ $service->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $service->activities }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($service->total_budget, 0, '', ' ') }} €</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Objectives -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-star text-university-warning me-2"></i>
                    Top 5 Objectifs les Plus Performants
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Objectif</th>
                                <th>Activités</th>
                                <th>Budget Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topObjectives as $objective)
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($objective->label, 30) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $objective->activities_count }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($objective->activities_sum_price ?? 0, 0, '', ' ') }} €</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history text-university-secondary me-2"></i>
                    Activités Récentes
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>ID</th>
                                <th><i class="bi bi-tag me-1"></i>Libellé</th>
                                <th><i class="bi bi-building me-1"></i>Service</th>
                                <th><i class="bi bi-bullseye me-1"></i>Objectif</th>
                                <th><i class="bi bi-currency-euro me-1"></i>Coût</th>
                                <th><i class="bi bi-flag me-1"></i>Statut</th>
                                <th><i class="bi bi-calendar me-1"></i>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                                <tr>
                                    <td><span class="badge bg-light text-dark">#{{ $activity->id }}</span></td>
                                    <td>
                                        <strong>{{ Str::limit($activity->label, 40) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-university-secondary text-white">
                                            {{ $activity->service->service ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($activity->objective->label ?? 'N/A', 25) }}</td>
                                    <td>
                                        <strong>{{ number_format($activity->price, 2, ',', ' ') }} €</strong>
                                    </td>
                                    <td>
                                        @if($activity->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
<script>
// Chart.js Configuration
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#475569';

// Activities by Service Chart
const activitiesByServiceCtx = document.getElementById('activitiesByServiceChart').getContext('2d');
const activitiesByServiceChart = new Chart(activitiesByServiceCtx, {
    type: 'bar',
    data: {
        labels: @json($activitiesByService->pluck('label')),
        datasets: [{
            label: 'Nombre d\'activités',
            data: @json($activitiesByService->pluck('value')),
            backgroundColor: '#1e3a8a',
            borderColor: '#1e40af',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' activités';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Actives', 'En attente'],
        datasets: [{
            data: [{{ $statusDistribution['active'] }}, {{ $statusDistribution['pending'] }}],
            backgroundColor: ['#16a34a', '#f59e0b'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Budget by Service Chart
const budgetCtx = document.getElementById('budgetChart').getContext('2d');
const budgetChart = new Chart(budgetCtx, {
    type: 'doughnut',
    data: {
        labels: @json($budgetByService->pluck('name')),
        datasets: [{
            data: @json($budgetByService->pluck('value')),
            backgroundColor: [
                '#1e3a8a', '#64748b', '#dc2626', '#16a34a', '#f59e0b',
                '#0ea5e9', '#8b5cf6', '#ec4899', '#f97316', '#84cc16'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 10
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.parsed) + ' €';
                    }
                }
            }
        }
    }
});

// Monthly Evolution Chart
const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
const evolutionChart = new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: @json($monthlyActivities->pluck('month')),
        datasets: [{
            label: 'Activités créées',
            data: @json($monthlyActivities->pluck('count')),
            borderColor: '#1e3a8a',
            backgroundColor: 'rgba(30, 58, 138, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Export Analytics Function
function exportAnalytics() {
    window.location.href = '{{ route("analytics.export") }}';
}

// Auto-refresh data every 5 minutes
setInterval(() => {
    // Refresh charts data via API calls
    fetch('{{ route("api.analytics.activities-by-service") }}')
        .then(response => response.json())
        .then(data => {
            activitiesByServiceChart.data.labels = data.map(item => item.label);
            activitiesByServiceChart.data.datasets[0].data = data.map(item => item.value);
            activitiesByServiceChart.update();
        });
}, 300000); // 5 minutes
</script>
@endpush
