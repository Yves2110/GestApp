<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport de Performance</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 10px;
    color: #1e293b;
    background: #fff;
    padding: 28px;
}
.report-header {
    border-bottom: 3px solid #2563eb;
    padding-bottom: 14px;
    margin-bottom: 22px;
    display: flex;
    justify-content: space-between;
}
.report-title    { font-size: 18px; font-weight: 700; color: #1e40af; }
.report-subtitle { font-size: 10px; color: #64748b; margin-top: 3px; }
.report-meta     { text-align: right; color: #64748b; font-size: 9px; line-height: 1.7; }

.section-title {
    font-size: 11px; font-weight: 700; color: #1e40af;
    border-bottom: 1px solid #dbeafe; padding-bottom: 5px;
    margin: 20px 0 12px; text-transform: uppercase; letter-spacing:.06em;
}

/* ── Taux de validation ──────────────────── */
.rate-box {
    text-align: center;
    border: 2px solid #16a34a;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 20px;
    background: #f0fdf4;
}
.rate-value { font-size: 48px; font-weight: 800; color: #15803d; line-height: 1; }
.rate-label { font-size: 11px; color: #166534; margin-top: 4px; font-weight: 600; }

/* ── Workflow bars ───────────────────────── */
.wf-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.wf-label { width: 100px; font-size: 9px; font-weight: 600; }
.wf-track { flex: 1; background: #e2e8f0; border-radius: 4px; height: 14px; }
.wf-fill  { height: 14px; border-radius: 4px; }
.wf-count { width: 30px; text-align: right; font-size: 9px; color: #64748b; }

/* ── Table services ──────────────────────── */
table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
thead tr { background: #1e40af; color: #fff; }
thead th { padding: 7px 10px; font-size: 9px; font-weight: 600; text-transform: uppercase; }
tbody tr { border-bottom: 1px solid #f1f5f9; }
tbody tr:nth-child(even) { background: #f8fafc; }
tbody td { padding: 7px 10px; font-size: 9px; }
.bar-cell { width: 120px; }
.mini-bar { background: #e2e8f0; border-radius: 3px; height: 8px; }
.mini-fill { height: 8px; border-radius: 3px; background: #2563eb; }

.report-footer {
    position: fixed; bottom: 0; left: 0; right: 0;
    border-top: 1px solid #e2e8f0; padding: 6px 28px;
    display: flex; justify-content: space-between;
    font-size: 8px; color: #94a3b8; background: #fff;
}
</style>
</head>
<body>

{{-- Header --}}
<div class="report-header">
    <div>
        <div class="report-title">Rapport de Performance</div>
        <div class="report-subtitle">GestApp University 3.0 — Analyse du workflow de validation</div>
    </div>
    <div class="report-meta">
        <strong>Généré le :</strong> {{ now()->format('d/m/Y à H:i') }}<br>
        <strong>Total activités :</strong> {{ $activities->count() }}<br>
        <strong>Budget total :</strong> {{ number_format($activities->sum('price'), 0, ',', ' ') }} FCFA
    </div>
</div>

{{-- Taux de validation --}}
<div class="rate-box">
    <div class="rate-value">{{ $validationRate }}%</div>
    <div class="rate-label">Taux de validation global</div>
</div>

{{-- Répartition workflow --}}
<div class="section-title">Répartition par statut de workflow</div>
@php
    $total   = $activities->count() ?: 1;
    $wfColors = [
        'draft'     => '#6c757d',
        'pending'   => '#fd7e14',
        'validated' => '#198754',
        'rejected'  => '#dc3545',
    ];
@endphp
@foreach($wfStats as $wf => $count)
    @php $pct = round($count / $total * 100); @endphp
    <div class="wf-row">
        <div class="wf-label">{{ $wfLabels[$wf] ?? $wf }}</div>
        <div class="wf-track">
            <div class="wf-fill" style="width:{{ $pct }}%;background:{{ $wfColors[$wf] ?? '#6c757d' }};"></div>
        </div>
        <div class="wf-count">{{ $count }} ({{ $pct }}%)</div>
    </div>
@endforeach

{{-- Performance par service --}}
<div class="section-title">Performance par service</div>
@php $maxSvc = $byService->max('total_count') ?: 1; @endphp
<table>
    <thead>
        <tr>
            <th style="width:35%">Service</th>
            <th style="width:12%">Total</th>
            <th style="width:12%">Validées</th>
            <th style="width:12%">Taux</th>
            <th>Répartition</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byService->where('total_count', '>', 0) as $service)
        @php
            $rate = $service->total_count > 0
                ? round($service->validated_count / $service->total_count * 100)
                : 0;
            $barPct = round($service->validated_count / $maxSvc * 100);
        @endphp
        <tr>
            <td><strong>{{ $service->label }}</strong></td>
            <td>{{ $service->total_count }}</td>
            <td>{{ $service->validated_count }}</td>
            <td><strong style="color:{{ $rate >= 70 ? '#15803d' : ($rate >= 40 ? '#d97706' : '#b91c1c') }};">{{ $rate }}%</strong></td>
            <td class="bar-cell">
                <div class="mini-bar">
                    <div class="mini-fill" style="width:{{ $barPct }}%;"></div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Footer --}}
<div class="report-footer">
    <span>GestApp University 3.0 — Confidentiel</span>
    <span>Rapport de performance — {{ now()->format('d/m/Y') }}</span>
</div>

</body>
</html>
