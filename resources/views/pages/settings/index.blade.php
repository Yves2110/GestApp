@extends('layouts.app')

@section('title', 'Paramètres - GestApp')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Paramètres</h1>
        <p class="page-subtitle">Administration réservée au Super Administrateur</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('settings.users') }}" class="text-decoration-none">
            <div class="card h-100" style="border-top:3px solid var(--clr-primary);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-people-fill me-3" style="font-size:2rem;color:var(--clr-primary);"></i>
                        <h5 class="mb-0">Utilisateurs</h5>
                    </div>
                    <p class="text-muted small mb-2">Désactiver un compte, réinitialiser les identifiants.</p>
                    <span class="badge bg-light text-dark">{{ $stats['active_users'] }}/{{ $stats['users'] }} actifs</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-4">
        <a href="{{ route('settings.permissions') }}" class="text-decoration-none">
            <div class="card h-100" style="border-top:3px solid var(--clr-info);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-shield-lock-fill me-3" style="font-size:2rem;color:var(--clr-info);"></i>
                        <h5 class="mb-0">Permissions</h5>
                    </div>
                    <p class="text-muted small mb-2">Attribuer des permissions aux rôles existants.</p>
                    <span class="badge bg-light text-dark">{{ $stats['roles'] }} rôles · {{ $stats['permissions'] }} permissions</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-4">
        <a href="{{ route('settings.audit') }}" class="text-decoration-none">
            <div class="card h-100" style="border-top:3px solid var(--clr-warning);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-clock-history me-3" style="font-size:2rem;color:var(--clr-warning-dark);"></i>
                        <h5 class="mb-0">Audit</h5>
                    </div>
                    <p class="text-muted small mb-2">Historique des interactions dans l'application.</p>
                    <span class="badge bg-light text-dark">{{ $stats['audit_logs'] }} entrées</span>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
