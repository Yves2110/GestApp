@extends('layouts.app')

@section('title', 'Monitoring Système - GestApp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-1">
            <i class="bi bi-activity text-university-primary me-2"></i>
            Monitoring Système
        </h2>
        <p class="text-muted mb-0">Surveillance de l'état de santé et des performances</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success" onclick="refreshMonitoring()">
            <i class="bi bi-arrow-clockwise me-1"></i>
            Actualiser
        </button>
        <button class="btn btn-outline-primary" onclick="exportMonitoringReport()">
            <i class="bi bi-download me-1"></i>
            Exporter
        </button>
    </div>
</div>

<!-- Health Status Overview -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card" id="healthOverview">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-heart-pulse text-university-primary me-2"></i>
                    État de Santé Global
                    <span id="overallStatus" class="badge ms-2">
                        <i class="bi bi-circle-fill me-1"></i>
                        Vérification...
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3" id="healthChecks">
                    <!-- Health checks will be loaded here -->
                    <div class="col-12 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Vérification de l'état du système...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Metrics -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-speedometer2 text-university-info me-2"></i>
                    Métriques Système
                </h5>
            </div>
            <div class="card-body">
                <div id="systemMetrics">
                    <!-- Metrics will be loaded here -->
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des métriques...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Active Alerts -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle text-university-warning me-2"></i>
                    Alertes Actives
                    <span id="alertCount" class="badge bg-danger ms-2">0</span>
                </h6>
            </div>
            <div class="card-body">
                <div id="activeAlerts">
                    <!-- Alerts will be loaded here -->
                    <p class="text-muted mb-0">Aucune alerte active</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-graph-up text-university-success me-2"></i>
                    Statistiques Rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Utilisateurs</small>
                            <strong id="totalUsers">-</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Activités</small>
                            <strong id="totalActivities">-</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Réponse DB</small>
                            <strong id="dbResponseTime">-</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Espace disque</small>
                            <strong id="diskUsage">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Logs -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-text text-university-secondary me-2"></i>
                        Logs Récents
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="filterLogs('all')">Tous</button>
                        <button class="btn btn-outline-danger" onclick="filterLogs('error')">Erreurs</button>
                        <button class="btn btn-outline-warning" onclick="filterLogs('warning')">Warnings</button>
                        <button class="btn btn-outline-info" onclick="filterLogs('info')">Info</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="recentLogs">
                    <!-- Logs will be loaded here -->
                    <div class="text-center">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des logs...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.health-check {
    transition: all 0.3s ease;
}
.health-check:hover {
    transform: translateY(-2px);
}
.log-entry {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}
.log-error {
    background-color: #fef2f2;
    border-left: 4px solid #dc2626;
}
.log-warning {
    background-color: #fffbeb;
    border-left: 4px solid #f59e0b;
}
.log-info {
    background-color: #f0f9ff;
    border-left: 4px solid #0ea5e9;
}
.metric-card {
    transition: all 0.2s ease;
}
.metric-card:hover {
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
</style>
@endpush

@push('scripts')
<script>
let monitoringData = {
    health: null,
    metrics: null,
    logs: null,
    alerts: []
};

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadMonitoringData();
    // Auto-refresh every 30 seconds
    setInterval(loadMonitoringData, 30000);
});

// Load all monitoring data
function loadMonitoringData() {
    Promise.all([
        fetch('/api/health').then(r => r.json()),
        fetch('/api/metrics').then(r => r.json()),
        fetch('/api/logs').then(r => r.json())
    ]).then(([health, metrics, logs]) => {
        monitoringData.health = health;
        monitoringData.metrics = metrics;
        monitoringData.logs = logs;
        
        updateHealthStatus(health);
        updateSystemMetrics(metrics);
        updateRecentLogs(logs);
        updateQuickStats(metrics);
        updateAlerts(health);
    }).catch(error => {
        console.error('Error loading monitoring data:', error);
        showError('Erreur lors du chargement des données de monitoring');
    });
}

// Update health status display
function updateHealthStatus(health) {
    const statusBadge = document.getElementById('overallStatus');
    const healthChecks = document.getElementById('healthChecks');
    
    // Update overall status
    statusBadge.className = `badge ms-2 bg-${health.overall_status === 'healthy' ? 'success' : 'danger'}`;
    statusBadge.innerHTML = `<i class="bi bi-circle-fill me-1"></i>${health.overall_status === 'healthy' ? 'Sain' : 'Problème'}`;
    
    // Update individual checks
    let checksHtml = '';
    for (const [check, data] of Object.entries(health.checks)) {
        const statusIcon = data.status === 'healthy' ? 'check-circle-fill' : 'x-circle-fill';
        const statusColor = data.status === 'healthy' ? 'success' : 'danger';
        
        checksHtml += `
            <div class="col-md-6 col-lg-4">
                <div class="card health-check border-${statusColor}">
                    <div class="card-body text-center">
                        <i class="bi bi-${statusIcon} text-${statusColor}" style="font-size: 2rem;"></i>
                        <h6 class="mt-2 mb-1">${check.charAt(0).toUpperCase() + check.slice(1)}</h6>
                        <small class="text-muted">${data.message}</small>
                        ${data.response_time ? `<br><small class="text-info">${data.response_time}ms</small>` : ''}
                    </div>
                </div>
            </div>
        `;
    }
    healthChecks.innerHTML = checksHtml;
}

// Update system metrics display
function updateSystemMetrics(metrics) {
    const metricsContainer = document.getElementById('systemMetrics');
    
    let metricsHtml = `
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card metric-card">
                    <div class="card-body">
                        <h6 class="card-title">Application</h6>
                        <table class="table table-sm">
                            <tr><td>Version</td><td>${metrics.application.version}</td></tr>
                            <tr><td>Environnement</td><td><span class="badge bg-info">${metrics.application.environment}</span></td></tr>
                            <tr><td>Laravel</td><td>${metrics.application.laravel_version}</td></tr>
                            <tr><td>PHP</td><td>${metrics.application.php_version}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card metric-card">
                    <div class="card-body">
                        <h6 class="card-title">Base de Données</h6>
                        <table class="table table-sm">
                            <tr><td>Connexions</td><td>${metrics.database.connections}</td></tr>
                            <tr><td>Requêtes lentes</td><td>${metrics.database.slow_queries}</td></tr>
                            <tr><td>Taille</td><td>${metrics.database.size}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card metric-card">
                    <div class="card-body">
                        <h6 class="card-title">Utilisateurs</h6>
                        <table class="table table-sm">
                            <tr><td>Total</td><td>${metrics.users.total}</td></tr>
                            <tr><td>Aujourd'hui</td><td>${metrics.users.active_today}</td></tr>
                            <tr><td>Cette semaine</td><td>${metrics.users.active_this_week}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card metric-card">
                    <div class="card-body">
                        <h6 class="card-title">Activités</h6>
                        <table class="table table-sm">
                            <tr><td>Total</td><td>${metrics.activities.total}</td></tr>
                            <tr><td>Aujourd'hui</td><td>${metrics.activities.created_today}</td></tr>
                            <tr><td>Actives</td><td><span class="badge bg-success">${metrics.activities.active}</span></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    metricsContainer.innerHTML = metricsHtml;
}

// Update recent logs display
function updateRecentLogs(logs) {
    const logsContainer = document.getElementById('recentLogs');
    
    if (logs.length === 0) {
        logsContainer.innerHTML = '<p class="text-muted text-center">Aucun log récent</p>';
        return;
    }
    
    let logsHtml = '<div class="table-responsive"><table class="table table-sm log-table">';
    logsHtml += '<thead><tr><th>Timestamp</th><th>Niveau</th><th>Message</th></tr></thead><tbody>';
    
    logs.forEach(log => {
        const logClass = `log-${log.level}`;
        const levelBadge = `bg-${log.level === 'error' ? 'danger' : log.level === 'warning' ? 'warning' : 'info'}`;
        
        logsHtml += `
            <tr class="log-entry ${logClass}" data-level="${log.level}">
                <td><small>${log.timestamp}</small></td>
                <td><span class="badge ${levelBadge}">${log.level.toUpperCase()}</span></td>
                <td><small>${log.message.substring(0, 200)}${log.message.length > 200 ? '...' : ''}</small></td>
            </tr>
        `;
    });
    
    logsHtml += '</tbody></table></div>';
    logsContainer.innerHTML = logsHtml;
}

// Update quick stats
function updateQuickStats(metrics) {
    document.getElementById('totalUsers').textContent = metrics.users.total;
    document.getElementById('totalActivities').textContent = metrics.activities.total;
    document.getElementById('dbResponseTime').textContent = 'N/A'; // Would need real implementation
    
    // Update disk usage if available
    if (monitoringData.health && monitoringData.health.checks.disk) {
        const diskUsage = monitoringData.health.checks.disk.details;
        document.getElementById('diskUsage').textContent = diskUsage.percentage + '%';
    }
}

// Update alerts
function updateAlerts(health) {
    const alertsContainer = document.getElementById('activeAlerts');
    const alertCount = document.getElementById('alertCount');
    
    // Generate alerts based on health checks
    const alerts = [];
    
    for (const [check, data] of Object.entries(health.checks)) {
        if (data.status === 'unhealthy') {
            alerts.push({
                type: 'critical',
                title: `Problème: ${check}`,
                message: data.message
            });
        }
    }
    
    // Add disk space warning
    if (health.checks.disk && health.checks.disk.details.percentage > 80) {
        alerts.push({
            type: 'warning',
            title: 'Espace disque faible',
            message: `Utilisation: ${health.checks.disk.details.percentage}%`
        });
    }
    
    alertCount.textContent = alerts.length;
    alertCount.className = alerts.length > 0 ? 'badge bg-danger ms-2' : 'badge bg-success ms-2';
    
    if (alerts.length === 0) {
        alertsContainer.innerHTML = '<p class="text-muted mb-0">Aucune alerte active</p>';
        return;
    }
    
    let alertsHtml = '';
    alerts.forEach(alert => {
        const alertClass = alert.type === 'critical' ? 'danger' : 'warning';
        alertsHtml += `
            <div class="alert alert-${alertClass} alert-sm mb-2">
                <strong>${alert.title}</strong><br>
                <small>${alert.message}</small>
            </div>
        `;
    });
    
    alertsContainer.innerHTML = alertsHtml;
}

// Filter logs by level
function filterLogs(level) {
    const logRows = document.querySelectorAll('.log-entry');
    
    logRows.forEach(row => {
        if (level === 'all' || row.dataset.level === level) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Refresh monitoring data
function refreshMonitoring() {
    loadMonitoringData();
    showSuccess('Données de monitoring actualisées');
}

// Export monitoring report
function exportMonitoringReport() {
    const report = {
        timestamp: new Date().toISOString(),
        health: monitoringData.health,
        metrics: monitoringData.metrics,
        logs: monitoringData.logs.slice(0, 100) // Last 100 logs
    };
    
    const blob = new Blob([JSON.stringify(report, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `gestapp-monitoring-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showSuccess('Rapport de monitoring exporté');
}

// Utility functions
function showSuccess(message) {
    // Create a temporary success alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        <i class="bi bi-check-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        if (alert.parentNode) {
            alert.parentNode.removeChild(alert);
        }
    }, 3000);
}

function showError(message) {
    // Create a temporary error alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        <i class="bi bi-exclamation-triangle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        if (alert.parentNode) {
            alert.parentNode.removeChild(alert);
        }
    }, 5000);
}
</script>
@endpush
