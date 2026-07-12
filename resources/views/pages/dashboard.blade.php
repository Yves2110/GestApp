@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Tableau de bord</h1>
        <p class="page-subtitle">
            Bienvenue, {{ Auth::user()->firstname }} —
            @switch(Auth::user()->role_id)
                @case(1) Super Administrateur @break
                @case(2) Président @break
                @case(3) Administrateur @break
                @case(4) Service @break
                @default Utilisateur
            @endswitch
        </p>
    </div>
    <div class="page-actions">
        <span class="badge badge-secondary">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->isoFormat('dddd D MMMM YYYY') }}
        </span>
        <a href="{{ route('Activites') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i>
            Nouvelle activité
        </a>
    </div>
</div>

{{-- KPI Cards --}}
<div class="content-grid content-grid-4 mb-6">
    <div class="card card-kpi card-kpi--primary">
        <div class="card-body d-flex align-items-center gap-4">
            <div class="kpi-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div>
                <div class="kpi-label">Total activités</div>
                <div class="kpi-value">{{ number_format($kpis['total']) }}</div>
            </div>
        </div>
    </div>

    <div class="card card-kpi card-kpi--success">
        <div class="card-body d-flex align-items-center gap-4">
            <div class="kpi-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="kpi-label">Actives</div>
                <div class="kpi-value">{{ number_format($kpis['active']) }}</div>
                @if($kpis['total'] > 0)
                    <div class="kpi-change kpi-change--up">
                        {{ round($kpis['active'] / $kpis['total'] * 100) }}% du total
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card card-kpi card-kpi--warning">
        <div class="card-body d-flex align-items-center gap-4">
            <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="kpi-label">En attente</div>
                <div class="kpi-value">{{ number_format($kpis['pending']) }}</div>
            </div>
        </div>
    </div>

    <div class="card card-kpi card-kpi--info">
        <div class="card-body d-flex align-items-center gap-4">
            <div class="kpi-icon"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="kpi-label">Budget total</div>
                <div class="kpi-value" style="font-size:1.4rem;">
                    {{ number_format($kpis['budget'], 0, ',', ' ') }}
                </div>
                <div class="text-xs text-muted">FCFA</div>
            </div>
        </div>
    </div>
</div>

{{-- Actions rapides --}}
<div class="section mb-6">
    <div class="section-header">
        <h2 class="section-title">Actions rapides</h2>
    </div>
    <div class="d-flex flex-wrap gap-3">
        <a href="{{ route('Activites') }}" class="btn btn-outline-primary">
            <i class="bi bi-lightning-charge"></i> Gérer les activités
        </a>
        <a href="{{ route('Objective') }}" class="btn btn-outline-primary">
            <i class="bi bi-bullseye"></i> Objectifs
        </a>
        @if(Auth::user()->role_id <= 3)
        <a href="{{ route('analytics') }}" class="btn btn-outline-primary">
            <i class="bi bi-graph-up-arrow"></i> Analytique
        </a>
        <a href="{{ route('export.config') }}" class="btn btn-outline-primary">
            <i class="bi bi-download"></i> Exporter
        </a>
        @endif
        @if(Auth::user()->role_id == 1)
        <a href="{{ route('register') }}" class="btn btn-outline-primary">
            <i class="bi bi-person-plus"></i> Nouvel utilisateur
        </a>
        @endif
    </div>
</div>

{{-- Grid : activités récentes + top services --}}
<div class="row g-4">
    {{-- Activités récentes --}}
    <div class="col-lg-8">
        <div class="table-wrapper">
            <div class="table-toolbar">
                <h5 class="table-toolbar-title">
                    <i class="bi bi-clock-history me-2 text-muted"></i>
                    Activités récentes
                </h5>
                <a href="{{ route('Activites') }}" class="btn btn-sm btn-ghost">
                    Voir tout <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @if($recentActivities->isEmpty())
                <x-empty-state
                    icon="bi-lightning-charge"
                    title="Aucune activité"
                    message="Commencez par créer une activité."
                >
                    <x-slot:action>
                        <a href="{{ route('Activites') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Créer une activité
                        </a>
                    </x-slot:action>
                </x-empty-state>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Activité</th>
                            <th class="hide-mobile">Service</th>
                            <th class="hide-mobile">Objectif</th>
                            <th>Statut</th>
                            <th class="col-actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                        <tr>
                            <td>
                                <div class="fw-medium text-sm">{{ \Illuminate\Support\Str::limit($activity->label, 50) }}</div>
                                @if($activity->periode)
                                    <div class="text-xs text-muted">{{ $activity->periode->label }}</div>
                                @endif
                            </td>
                            <td class="hide-mobile">
                                <span class="text-sm">{{ $activity->service->label ?? '—' }}</span>
                            </td>
                            <td class="hide-mobile">
                                <span class="text-xs text-muted">{{ \Illuminate\Support\Str::limit($activity->objective->label ?? '—', 30) }}</span>
                            </td>
                            <td>
                                @if($activity->status)
                                    <span class="badge badge-status badge-status--active">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge badge-status badge-status--pending">
                                        <i class="bi bi-hourglass me-1"></i>En attente
                                    </span>
                                @endif
                            </td>
                            <td class="col-actions">
                                <a href="{{ route('activites.edit', $activity->id) }}"
                                   class="btn btn-sm btn-ghost btn-icon" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Top services --}}
    <div class="col-lg-4">
        <x-card title="Répartition par service" icon="bi-building">
            @if($services->isEmpty())
                <x-empty-state icon="bi-building" title="Aucun service" />
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($services->sortByDesc('activities_count')->take(6) as $service)
                    @php
                        $pct = $kpis['total'] > 0
                            ? round($service->activities_count / $kpis['total'] * 100)
                            : 0;
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-sm fw-medium">{{ $service->label }}</span>
                            <span class="text-xs text-muted">{{ $service->activities_count }} activité{{ $service->activities_count > 1 ? 's' : '' }}</span>
                        </div>
                        <div class="progress" style="height:6px; border-radius:3px; background:var(--clr-gray-100);">
                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width:{{ $pct }}%; background:var(--clr-primary); border-radius:3px;"
                                 aria-valuenow="{{ $pct }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </x-card>
    </div>
</div>
{{-- /Grid --}}
@endsection
