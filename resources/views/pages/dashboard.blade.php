@extends('layouts.app')

@section('title', 'Tableau de bord - GestApp')

@section('content')
<div class="row g-4">
    <!-- Welcome Section - Optimized Space -->
    <div class="col-12">
        <div class="university-header rounded-3 p-3 p-md-4 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7 col-lg-8">
                    <h2 class="h4 h3-md mb-2">
                        <i class="bi bi-person-circle me-2"></i>
                        Bienvenue, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                    </h2>
                    <p class="mb-0 opacity-75 small small-md">
                        @switch(Auth::user()->role_id)
                            @case(1)
                                Super Administrateur - Accès complet au système
                                @break
                            @case(2)
                                Président - Gestion stratégique et supervision
                                @break
                            @case(3)
                                Administrateur - Gestion opérationnelle
                                @break
                            @case(4)
                                Service - Gestion des activités de service
                                @break
                            @default
                                Utilisateur - Accès limité
                        @endswitch
                    </p>
                </div>
                <div class="col-md-5 col-lg-4 text-md-end">
                    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-md-end gap-2">
                        <span class="badge bg-white text-university-primary px-3 py-2">
                            <i class="bi bi-calendar3 me-1"></i>
                            <span class="d-none d-sm-inline">{{ now()->format('d/m/Y') }}</span>
                            <span class="d-sm-none">{{ now()->format('d/m') }}</span>
                        </span>
                        <span class="badge bg-white text-university-primary px-3 py-2">
                            <i class="bi bi-clock me-1"></i>
                            {{ now()->format('H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Optimized Space -->
    <div class="col-6 col-md-3">
        <div class="card university-stats h-100">
            <div class="card-body text-center p-2 p-md-3">
                <div class="text-university-primary mb-2 mb-md-3">
                    <i class="bi bi-bullseye" style="font-size: 1.5rem; font-size: clamp(1.25rem, 4vw, 2.5rem);"></i>
                </div>
                <h3 class="h5 h4-md mb-1 mb-md-2">{{ App\Models\Objective::count() }}</h3>
                <p class="text-muted mb-0 small">Objectifs Globaux</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card university-stats h-100">
            <div class="card-body text-center p-2 p-md-3">
                <div class="text-university-secondary mb-2 mb-md-3">
                    <i class="bi bi-diagram-3" style="font-size: 1.5rem; font-size: clamp(1.25rem, 4vw, 2.5rem);"></i>
                </div>
                <h3 class="h5 h4-md mb-1 mb-md-2">{{ App\Models\under_objective::count() }}</h3>
                <p class="text-muted mb-0 small">Sous-Objectifs</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card university-stats h-100">
            <div class="card-body text-center p-2 p-md-3">
                <div class="text-university-success mb-2 mb-md-3">
                    <i class="bi bi-activity" style="font-size: 1.5rem; font-size: clamp(1.25rem, 4vw, 2.5rem);"></i>
                </div>
                <h3 class="h5 h4-md mb-1 mb-md-2">{{ App\Models\Activities::count() }}</h3>
                <p class="text-muted mb-0 small">Activités</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card university-stats h-100">
            <div class="card-body text-center p-2 p-md-3">
                <div class="text-university-warning mb-2 mb-md-3">
                    <i class="bi bi-building" style="font-size: 1.5rem; font-size: clamp(1.25rem, 4vw, 2.5rem);"></i>
                </div>
                <h3 class="h5 h4-md mb-1 mb-md-2">{{ App\Models\service::count() }}</h3>
                <p class="text-muted mb-0 small">Services</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning-fill text-university-warning me-2"></i>
                    Actions Rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('Objective') }}" class="btn btn-outline-primary w-100 text-start py-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            <div>
                                <strong>Nouvel Objectif</strong>
                                <div class="small text-muted">Créer un objectif stratégique</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('Activites') }}" class="btn btn-outline-success w-100 text-start py-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            <div>
                                <strong>Nouvelle Activité</strong>
                                <div class="small text-muted">Ajouter une activité</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('Guide') }}" class="btn btn-outline-info w-100 text-start py-3">
                            <i class="bi bi-file-earmark-plus me-2"></i>
                            <div>
                                <strong>Ajouter un Guide</strong>
                                <div class="small text-muted">Téléverser un document</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('Under_Objective') }}" class="btn btn-outline-secondary w-100 text-start py-3">
                            <i class="bi bi-diagram-3 me-2"></i>
                            <div>
                                <strong>Sous-Objectifs</strong>
                                <div class="small text-muted">Gérer les sous-objectifs</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history text-university-info me-2"></i>
                    Activités Récentes
                </h5>
            </div>
            <div class="card-body">
                @if($recentActivities = App\Models\Activities::with(['service', 'objective'])->latest()->take(5)->get())
                    @if($recentActivities->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivities as $activity)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ Str::limit($activity->label, 50) }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-building me-1"></i>{{ $activity->service->service ?? 'N/A' }}
                                            </small>
                                        </div>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Aucune activité récente</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Services Overview -->
    @if($services ?? null)
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building text-university-secondary me-2"></i>
                        Services Universitaires
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($services as $service)
                            <div class="col-md-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-building text-university-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="card-title mb-1">{{ $service->service }}</h6>
                                        <small class="text-muted">
                                            {{ $service->activities()->count() }} activité(s)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- System Status -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear text-university-secondary me-2"></i>
                    État du Système
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Base de données</h6>
                                <small class="text-muted">Opérationnelle</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Sécurité</h6>
                                <small class="text-muted">Activée</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Performances</h6>
                                <small class="text-muted">Optimales</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Sauvegardes</h6>
                                <small class="text-muted">Automatiques</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
