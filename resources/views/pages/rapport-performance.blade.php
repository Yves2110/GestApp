@extends('layouts.app')

@section('title', 'Rapport de performance')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('analytics') }}">Analytics</a></li>
    <li class="breadcrumb-item active">Rapport de performance</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Rapport de performance</h1>
        <p class="page-subtitle">
            Période : {{ $startDate->format('d/m/Y') }} — {{ $endDate->format('d/m/Y') }}
            (6 derniers mois)
        </p>
    </div>
    <div class="page-actions">
        <button class="btn btn-ghost btn-sm no-print" onclick="window.print()">
            <i class="bi bi-printer"></i>
            <span class="hide-mobile">Imprimer</span>
        </button>
        <a href="{{ route('export.pdf.performance') }}" class="btn btn-ghost btn-sm" target="_blank">
            <i class="bi bi-file-earmark-pdf text-danger"></i>
            <span class="hide-mobile">PDF</span>
        </a>
        <a href="{{ route('export.activities.csv') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-download"></i>
            <span class="hide-mobile">CSV</span>
        </a>
    </div>
</div>

{{-- ===== KPIs COMPARATIFS ===== --}}
<div class="kpi-grid mb-6">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--clr-primary-light);color:var(--clr-primary);">
            <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $totalCurrent }}</div>
            <div class="kpi-label">Activités (6 mois)</div>
        </div>
        <div class="kpi-trend {{ $growthRate >= 0 ? 'kpi-trend--up' : 'kpi-trend--down' }}">
            <i class="bi bi-arrow-{{ $growthRate >= 0 ? 'up' : 'down' }}-short me-1"></i>
            {{ abs($growthRate) }}% vs période préc.
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#f0fdf4;color:var(--clr-success);">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $validationRate }}%</div>
            <div class="kpi-label">Taux de validation</div>
        </div>
        <div class="kpi-trend kpi-trend--up">
            <i class="bi bi-check2-all me-1"></i>{{ $workflowBreakdown[\App\Models\Activity::WF_VALIDATED] }} validées
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--clr-primary-light);color:var(--clr-primary);">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ number_format($budgetCurrent / 1000000, 1) }}M</div>
            <div class="kpi-label">Budget engagé (FCFA)</div>
        </div>
        <div class="kpi-trend {{ $budgetGrowth >= 0 ? 'kpi-trend--up' : 'kpi-trend--down' }}">
            <i class="bi bi-arrow-{{ $budgetGrowth >= 0 ? 'up' : 'down' }}-short me-1"></i>
            {{ abs($budgetGrowth) }}% vs période préc.
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fffbeb;color:var(--clr-warning);">
            <i class="bi bi-calculator"></i>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ number_format($avgBudget / 1000, 0) }}k</div>
            <div class="kpi-label">Budget moyen / activité</div>
        </div>
        <div class="kpi-trend kpi-trend--up">
            <i class="bi bi-graph-up me-1"></i>{{ $totalPrev }} activités préc.
        </div>
    </div>
</div>

{{-- ===== WORKFLOW BREAKDOWN ===== --}}
@php
    $wfTotal  = array_sum($workflowBreakdown) ?: 1;
    $wfConfig = [
        \App\Models\Activity::WF_DRAFT     => ['Brouillon',  'var(--clr-gray-400)'],
        \App\Models\Activity::WF_PENDING   => ['En attente', 'var(--clr-warning)'],
        \App\Models\Activity::WF_VALIDATED => ['Validé',     'var(--clr-success)'],
        \App\Models\Activity::WF_REJECTED  => ['Rejeté',     'var(--clr-danger)'],
    ];
@endphp

<div class="row g-4 mb-6">
    <div class="col-lg-5">
        <x-card title="Répartition workflow (6 mois)" icon="bi-diagram-2">
            <div class="d-flex flex-column gap-3">
                @foreach($wfConfig as $status => [$label, $color])
                @php
                    $count = $workflowBreakdown[$status];
                    $pct   = round($count / $wfTotal * 100);
                @endphp
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-sm">{{ $label }}</span>
                        <span class="text-sm fw-semi">
                            {{ $count }}
                            <span class="text-muted">({{ $pct }}%)</span>
                        </span>
                    </div>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Barre empilée visuelle --}}
            <div class="d-flex mt-4" style="height:12px;border-radius:6px;overflow:hidden;">
                @foreach($wfConfig as $status => [$label, $color])
                @php $pct = round($workflowBreakdown[$status] / $wfTotal * 100); @endphp
                @if($pct > 0)
                <div style="width:{{ $pct }}%;background:{{ $color }};"
                     title="{{ $label }}: {{ $workflowBreakdown[$status] }}"></div>
                @endif
                @endforeach
            </div>
        </x-card>
    </div>

    <div class="col-lg-7">
        {{-- Bar chart CSS — évolution mensuelle --}}
        <x-card title="Évolution mensuelle des activités" icon="bi-bar-chart-line">
            @if($monthlyEvolution->isEmpty())
                <x-empty-state icon="bi-bar-chart" title="Pas de données" message="Aucune activité sur cette période." />
            @else
            @php $maxVal = $monthlyEvolution->max('total') ?: 1; @endphp
            <div class="d-flex align-items-end gap-2" style="height:140px;overflow-x:auto;padding-bottom:4px;">
                @foreach($monthlyEvolution as $m)
                @php
                    $hTotal     = round($m->total     / $maxVal * 120);
                    $hValidated = $m->total > 0 ? round($m->validated / $m->total * $hTotal) : 0;
                @endphp
                <div class="d-flex flex-column align-items-center gap-1 flex-shrink-0" style="min-width:44px;">
                    <span class="text-xs text-muted fw-semi">{{ $m->total }}</span>
                    <div style="width:28px;position:relative;height:{{ max($hTotal,4) }}px;background:var(--clr-primary-light);border-radius:3px 3px 0 0;overflow:hidden;"
                         title="{{ $m->month }}: {{ $m->total }} total, {{ $m->validated }} validées">
                        <div style="position:absolute;bottom:0;width:100%;height:{{ $hValidated }}px;background:var(--clr-success);"></div>
                    </div>
                    <span class="text-xs text-muted" style="white-space:nowrap;">
                        {{ \Illuminate\Support\Str::after($m->month, '-') }}/{{ \Illuminate\Support\Str::before($m->month, '-') }}
                    </span>
                </div>
                @endforeach
            </div>
            <div class="d-flex gap-4 mt-3">
                <span class="text-xs d-flex align-items-center gap-1">
                    <span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:var(--clr-primary-light);"></span>
                    Total
                </span>
                <span class="text-xs d-flex align-items-center gap-1">
                    <span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:var(--clr-success);"></span>
                    Validées
                </span>
            </div>
            @endif
        </x-card>
    </div>
</div>

{{-- ===== PERFORMANCE PAR SERVICE ===== --}}
<x-card title="Performance par service" icon="bi-building" class="mb-6">
    @if($servicePerf->isEmpty())
        <x-empty-state icon="bi-building" title="Aucune donnée" message="Aucune activité sur cette période." />
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th style="text-align:center;">Total</th>
                    <th style="text-align:center;">Validées</th>
                    <th>Taux</th>
                    <th style="text-align:right;" class="hide-mobile">Budget</th>
                </tr>
            </thead>
            <tbody>
            @foreach($servicePerf as $s)
            @php
                $rateCls = match(true) {
                    $s->rate >= 75 => 'var(--clr-success)',
                    $s->rate >= 40 => 'var(--clr-warning)',
                    default        => 'var(--clr-danger)',
                };
            @endphp
            <tr>
                <td class="text-sm text-muted">{{ $loop->iteration }}</td>
                <td>
                    <div class="fw-medium text-sm">{{ \Illuminate\Support\Str::limit($s->name, 35) }}</div>
                </td>
                <td style="text-align:center;"><span class="badge badge-secondary">{{ $s->total }}</span></td>
                <td style="text-align:center;"><span class="badge badge-primary">{{ $s->validated }}</span></td>
                <td style="min-width:120px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress-bar-track flex-grow-1">
                            <div class="progress-bar-fill" style="width:{{ $s->rate }}%;background:{{ $rateCls }};"></div>
                        </div>
                        <span class="text-xs fw-semi" style="width:32px;flex-shrink:0;">{{ $s->rate }}%</span>
                    </div>
                </td>
                <td class="text-sm hide-mobile" style="text-align:right;">
                    {{ number_format($s->budget / 1000000, 2, ',', ' ') }}M
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-card>

@endsection
