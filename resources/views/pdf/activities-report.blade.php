<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport des Activités</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 10px;
    color: #1e293b;
    background: #fff;
    padding: 24px;
}

/* ── Header ──────────────────────────────── */
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 3px solid #2563eb;
    padding-bottom: 14px;
    margin-bottom: 20px;
}
.report-title { font-size: 20px; font-weight: 700; color: #1e40af; }
.report-subtitle { font-size: 11px; color: #64748b; margin-top: 3px; }
.report-meta { text-align: right; color: #64748b; font-size: 9px; line-height: 1.6; }
.report-meta strong { color: #1e293b; }

/* ── KPI Cards ───────────────────────────── */
.kpi-row {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.kpi-card {
    flex: 1;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 14px;
    border-top: 3px solid #2563eb;
}
.kpi-card.success { border-top-color: #16a34a; }
.kpi-card.warning { border-top-color: #d97706; }
.kpi-card.danger  { border-top-color: #dc2626; }
.kpi-card.info    { border-top-color: #0891b2; }
.kpi-label { font-size: 8px; text-transform: uppercase; letter-spacing: .05em; color: #64748b; font-weight: 600; }
.kpi-value { font-size: 22px; font-weight: 700; color: #1e293b; line-height: 1.2; margin-top: 3px; }
.kpi-sub   { font-size: 9px; color: #94a3b8; margin-top: 2px; }

/* ── Section ─────────────────────────────── */
.section-title {
    font-size: 12px;
    font-weight: 700;
    color: #1e40af;
    border-bottom: 1px solid #dbeafe;
    padding-bottom: 5px;
    margin-bottom: 12px;
    margin-top: 20px;
    text-transform: uppercase;
    letter-spacing: .06em;
}

/* ── Table ───────────────────────────────── */
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
thead tr { background: #1e40af; color: #fff; }
thead th {
    padding: 7px 8px;
    text-align: left;
    font-size: 8.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    white-space: nowrap;
}
tbody tr { border-bottom: 1px solid #f1f5f9; }
tbody tr:nth-child(even) { background: #f8fafc; }
tbody td { padding: 6px 8px; font-size: 9px; }

/* ── Badge workflow ──────────────────────── */
.badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 12px;
    font-size: 8px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.badge-draft     { background: #f1f5f9; color: #475569; }
.badge-pending   { background: #fff7ed; color: #c2410c; }
.badge-validated { background: #f0fdf4; color: #15803d; }
.badge-rejected  { background: #fef2f2; color: #b91c1c; }

/* ── Progress bar ────────────────────────── */
.progress-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.progress-label { width: 140px; font-size: 9px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.progress-track { flex: 1; background: #e2e8f0; border-radius: 4px; height: 8px; }
.progress-fill  { height: 8px; border-radius: 4px; background: #2563eb; }
.progress-count { width: 24px; text-align: right; font-size: 9px; color: #64748b; }

/* ── Footer ──────────────────────────────── */
.report-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    border-top: 1px solid #e2e8f0;
    padding: 6px 24px;
    display: flex;
    justify-content: space-between;
    font-size: 8px;
    color: #94a3b8;
    background: #fff;
}

/* ── Page break ──────────────────────────── */
.page-break { page-break-after: always; }
</style>
</head>
<body>

{{-- Header --}}
<div class="report-header">
    <div>
        <div class="report-title">Rapport des Activités</div>
        <div class="report-subtitle">GestApp University 3.0 — Rapport automatisé</div>
    </div>
    <div class="report-meta">
        <strong>Généré le :</strong> {{ now()->format('d/m/Y à H:i') }}<br>
        <strong>Total activités :</strong> {{ $stats['total'] }}<br>
        <strong>Budget total :</strong> {{ number_format($stats['budget'], 0, ',', ' ') }} FCFA
    </div>
</div>

{{-- KPI Cards --}}
<div class="kpi-row">
    <div class="kpi-card">
        <div class="kpi-label">Total</div>
        <div class="kpi-value">{{ $stats['total'] }}</div>
        <div class="kpi-sub">activités</div>
    </div>
    <div class="kpi-card success">
        <div class="kpi-label">Validées</div>
        <div class="kpi-value">{{ $stats['validated'] }}</div>
        <div class="kpi-sub">{{ $stats['total'] > 0 ? round($stats['validated']/$stats['total']*100) : 0 }}% du total</div>
    </div>
    <div class="kpi-card warning">
        <div class="kpi-label">En attente</div>
        <div class="kpi-value">{{ $stats['pending'] }}</div>
        <div class="kpi-sub">en cours de validation</div>
    </div>
    <div class="kpi-card danger">
        <div class="kpi-label">Rejetées</div>
        <div class="kpi-value">{{ $stats['rejected'] }}</div>
        <div class="kpi-sub">à corriger</div>
    </div>
    <div class="kpi-card info">
        <div class="kpi-label">Budget</div>
        <div class="kpi-value" style="font-size:14px;">{{ number_format($stats['budget'], 0, ',', ' ') }}</div>
        <div class="kpi-sub">FCFA</div>
    </div>
</div>

{{-- Répartition par service --}}
<div class="section-title">Répartition par service</div>
@php $maxCount = $byService->map(fn($g) => $g->count())->max() ?: 1; @endphp
@foreach($byService as $serviceName => $group)
    @php $pct = round($group->count() / $maxCount * 100); @endphp
    <div class="progress-row">
        <div class="progress-label">{{ $serviceName }}</div>
        <div class="progress-track">
            <div class="progress-fill" style="width:{{ $pct }}%;"></div>
        </div>
        <div class="progress-count">{{ $group->count() }}</div>
    </div>
@endforeach

{{-- Table des activités --}}
<div class="section-title" style="margin-top:24px;">Liste des activités</div>
<table>
    <thead>
        <tr>
            <th style="width:25%">Activité</th>
            <th style="width:14%">Service</th>
            <th style="width:18%">Objectif</th>
            <th style="width:10%">Période</th>
            <th style="width:10%">Indicateur</th>
            <th style="width:10%">Budget</th>
            <th style="width:8%">Statut</th>
            <th style="width:5%">Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($activities as $activity)
        <tr>
            <td>{{ \Illuminate\Support\Str::limit($activity->label, 55) }}</td>
            <td>{{ $activity->service->label ?? '—' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($activity->objective->label ?? '—', 40) }}</td>
            <td>{{ $activity->periode->label ?? '—' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($activity->indicator ?? '—', 20) }}</td>
            <td>{{ number_format($activity->price ?? 0, 0, ',', ' ') }}</td>
            <td>
                <span class="badge badge-{{ $activity->workflow_status ?? 'draft' }}">
                    @switch($activity->workflow_status)
                        @case('validated') Validé @break
                        @case('pending')   Attente @break
                        @case('rejected')  Rejeté @break
                        @default           Brouill.
                    @endswitch
                </span>
            </td>
            <td>{{ $activity->created_at->format('d/m/y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Footer --}}
<div class="report-footer">
    <span>GestApp University 3.0 — Confidentiel</span>
    <span>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</span>
</div>

</body>
</html>
