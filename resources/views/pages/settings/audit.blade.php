@extends('layouts.app')

@section('title', "Journal d'audit - GestApp")

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('settings') }}">Paramètres</a></li>
    <li class="breadcrumb-item active">Journal d'audit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Journal d'audit</h1>
        <p class="page-subtitle">Historique des interactions dans l'application</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('settings') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('settings.audit') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Utilisateur</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                            {{ $user->firstname }} {{ $user->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Action</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') == $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Du</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Au</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
                <a href="{{ route('settings.audit') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th class="d-none d-md-table-cell">Description</th>
                        <th class="d-none d-lg-table-cell">Méthode</th>
                        <th class="d-none d-xl-table-cell">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="small text-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="small">
                                @if ($log->user)
                                    {{ $log->user->firstname }} {{ $log->user->lastname }}
                                @else
                                    <span class="text-muted">Système</span>
                                @endif
                            </td>
                            <td><span class="badge bg-university-secondary">{{ $log->action }}</span></td>
                            <td class="d-none d-md-table-cell small">{{ $log->description }}</td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge bg-light text-dark">{{ $log->method }}</span>
                            </td>
                            <td class="d-none d-xl-table-cell small text-muted">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucune entrée d'audit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $logs->links() }}
</div>
@endsection
