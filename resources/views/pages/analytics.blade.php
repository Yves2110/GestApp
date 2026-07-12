@extends('layouts.app')

@section('title', 'Analytics')

@section('breadcrumb')
    <li class="breadcrumb-item active">Analytics</li>
@endsection

@section('content')
{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Analytics</h1>
        <p class="page-subtitle">Statistiques et indicateurs de performance</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('analytics.performance') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-speedometer2"></i>
            <span class="hide-mobile">Performance</span>
        </a>
        <a href="{{ route('export.config') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-download"></i>
            <span class="hide-mobile">Exporter</span>
        </a>
        <button class="btn btn-ghost btn-sm no-print" onclick="window.print()">
            <i class="bi bi-printer"></i>
            <span class="hide-mobile">Imprimer</span>
        </button>
    </div>
</div>

{{-- ===== KPI CARDS GÉNÉRAUX ===== --}}
<div class="kpi-grid mb-6">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--clr-primary-light);color:var(--clr-primary);">
            <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ number_format($stats['total_activities']) }}</div>
            <div class="kpi-label">Activités totales</div>
        </div>
        <div class="kpi-trend kpi-trend--up">
            <i class="bi bi-check-circle me-1"></i>{{ $stats['active_activities'] }} actives
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fffbeb;color:var(--clr-warning);">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $workflowStats[\App\Models\Activity::WF_PENDING] }}</div>
            <div class="kpi-label">En attente de validation</div>
        </div>
        <div class="kpi-trend {{ $workflowStats[\App\Models\Activity::WF_PENDING] > 0 ? 'kpi-trend--warn' : 'kpi-trend--up' }}">
            <i class="bi bi-diagram-2 me-1"></i>{{ $workflowStats[\App\Models\Activity::WF_REJECTED] }} rejetées
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
            <i class="bi bi-check2-all me-1"></i>{{ $workflowStats[\App\Models\Activity::WF_VALIDATED] }} validées
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--clr-primary-light);color:var(--clr-primary);">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ number_format($stats['total_budget'] / 1000000, 1) }}M</div>
            <div class="kpi-label">Budget total (FCFA)</div>
        </div>
        <div class="kpi-trend kpi-trend--up">
            <i class="bi bi-building me-1"></i>{{ $stats['total_services'] }} services
        </div>
    </div>
</div>

{{-- ===== WORKFLOW STATUS BARS ===== --}}
@php
    $wfTotal = array_sum($workflowStats);
    $wfColors = [
        \App\Models\Activity::WF_DRAFT     => ['var(--clr-gray-400)',  'Brouillon'],
        \App\Models\Activity::WF_PENDING   => ['var(--clr-warning)',   'En attente'],
        \App\Models\Activity::WF_VALIDATED => ['var(--clr-success)',   'Validé'],
        \App\Models\Activity::WF_REJECTED  => ['var(--clr-danger)',    'Rejeté'],
    ];
@endphp
<div class="row g-4 mb-6">
    <div class="col-lg-6">
        <x-card title="Répartition du workflow" icon="bi-diagram-2">
            <div class="d-flex flex-column gap-3">
                @foreach($wfColors as $status => [$color, $label])
                @php $count = $workflowStats[$status]; $pct = $wfTotal > 0 ? round($count / $wfTotal * 100) : 0; @endphp
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-sm">{{ $label }}</span>
                        <span class="text-sm fw-semi">{{ $count }} <span class="text-muted">({{ $pct }}%)</span></span>
                    </div>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </x-card>
    </div>

    <div class="col-lg-6">
        <x-card title="Top 5 services" icon="bi-building">
            <div class="d-flex flex-column gap-3">
                @php $maxAct = $topServices->max('activities') ?: 1; @endphp
                @forelse($topServices as $s)
                @php $pct = round($s->activities / $maxAct * 100); @endphp
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-sm">{{ \Illuminate\Support\Str::limit($s->name, 30) }}</span>
                        <span class="text-sm fw-semi">{{ $s->activities }} activités</span>
                    </div>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" style="width:{{ $pct }}%;background:var(--clr-primary);"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-sm">Aucune donnée.</p>
                @endforelse
            </div>
        </x-card>
    </div>
</div>

{{-- ===== ACTIVITÉS PAR PÉRIODE ===== --}}
<div class="row g-4 mb-6">
    <div class="col-lg-8">
        <x-card title="Activités par période" icon="bi-calendar3">
            @if($objectivesByPeriod->isEmpty())
                <x-empty-state icon="bi-calendar3" title="Aucune donnée" message="Pas encore d'activités par période." />
            @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Période</th>
                            <th style="text-align:center;">Activités</th>
                            <th style="text-align:right;">Budget</th>
                            <th>Répartition</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $maxPeriod = $objectivesByPeriod->max('activities') ?: 1; @endphp
                    @foreach($objectivesByPeriod as $row)
                    <tr>
                        <td class="fw-medium text-sm">{{ $row->period }}</td>
                        <td style="text-align:center;"><span class="badge badge-primary">{{ $row->activities }}</span></td>
                        <td class="text-sm" style="text-align:right;">{{ number_format($row->total_budget, 0, ',', ' ') }} FCFA</td>
                        <td style="width:140px;">
                            <div class="progress-bar-track">
                                <div class="progress-bar-fill" style="width:{{ round($row->activities/$maxPeriod*100) }}%;background:var(--clr-primary);"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </x-card>
    </div>

    <div class="col-lg-4">
        <x-card title="Top objectifs" icon="bi-bullseye">
            <div class="d-flex flex-column gap-3">
                @forelse($topObjectives as $obj)
                <div class="d-flex align-items-center gap-3">
                    <div style="width:28px;height:28px;border-radius:50%;background:var(--clr-primary-light);color:var(--clr-primary);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-grow-1" style="min-width:0;">
                        <div class="text-sm fw-medium" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ \Illuminate\Support\Str::limit($obj->label, 35) }}
                        </div>
                        <div class="text-xs text-muted">{{ $obj->activities_count }} activité{{ $obj->activities_count > 1 ? 's' : '' }}</div>
                    </div>
                    <span class="badge badge-secondary flex-shrink-0">{{ number_format(($obj->activities_sum_price ?? 0)/1000, 0) }}k</span>
                </div>
                @empty
                <p class="text-muted text-sm">Aucun objectif.</p>
                @endforelse
            </div>
        </x-card>
    </div>
</div>

{{-- ===== ÉVOLUTION MENSUELLE (bar chart CSS) ===== --}}
@if($monthlyActivities->isNotEmpty())
<x-card title="Évolution mensuelle (12 derniers mois)" icon="bi-bar-chart-line" class="mb-6">
    @php $maxMonth = $monthlyActivities->max('count') ?: 1; @endphp
    <div class="d-flex align-items-end gap-2" style="height:120px;overflow-x:auto;padding-bottom:4px;">
        @foreach($monthlyActivities as $m)
        @php $h = round($m->count / $maxMonth * 100); @endphp
        <div class="d-flex flex-column align-items-center gap-1 flex-shrink-0" style="min-width:40px;">
            <span class="text-xs text-muted fw-semi">{{ $m->count }}</span>
            <div style="width:28px;height:{{ max($h, 4) }}px;background:var(--clr-primary);border-radius:3px 3px 0 0;transition:height .3s ease;"
                 title="{{ $m->month }} — {{ $m->count }} activités"></div>
            <span class="text-xs text-muted" style="white-space:nowrap;">{{ \Illuminate\Support\Str::after($m->month, '-') }}/{{ \Illuminate\Support\Str::before($m->month, '-') }}</span>
        </div>
        @endforeach
    </div>
</x-card>
@endif

{{-- ===== ACTIVITÉS RÉCENTES ===== --}}
<x-card title="Activités récentes" icon="bi-clock-history" class="mb-6">
    @if($recentActivities->isEmpty())
        <x-empty-state icon="bi-inbox" title="Aucune activité" message="Aucune activité enregistrée." />
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th class="hide-mobile">Service</th>
                    <th class="hide-mobile">Période</th>
                    <th>Workflow</th>
                    <th style="text-align:right;" class="hide-mobile">Budget</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($recentActivities as $a)
            @php
                $wfCls = match($a->workflow_status) {
                    'draft'     => 'badge-status--draft',
                    'pending'   => 'badge-status--pending',
                    'validated' => 'badge-status--active',
                    'rejected'  => 'badge-status--rejected',
                    default     => 'badge-secondary',
                };
            @endphp
            <tr>
                <td>
                    <div class="text-sm fw-medium">{{ \Illuminate\Support\Str::limit($a->label, 50) }}</div>
                    <div class="text-xs text-muted hide-mobile">{{ $a->created_at->diffForHumans() }}</div>
                </td>
                <td class="hide-mobile"><span class="badge badge-secondary">{{ $a->service->label ?? '—' }}</span></td>
                <td class="text-sm hide-mobile text-muted">{{ $a->periode->label ?? '—' }}</td>
                <td><span class="badge badge-status {{ $wfCls }}">{{ $a->workflowLabel }}</span></td>
                <td class="text-sm hide-mobile" style="text-align:right;">{{ $a->price ? number_format($a->price, 0, ',', ' ') . ' FCFA' : '—' }}</td>
                <td>
                    <a href="{{ route('activites.show', $a->id) }}" class="btn btn-ghost btn-icon btn-sm">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-card>

@endsection
