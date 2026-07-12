@extends('layouts.app')

@section('title', 'Gestion des utilisateurs - GestApp')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('settings') }}">Paramètres</a></li>
    <li class="breadcrumb-item active">Utilisateurs</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Gestion des utilisateurs</h1>
        <p class="page-subtitle">Désactivation des comptes et réinitialisation des identifiants</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('settings') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

@if(session('message'))
    <x-alert type="success">{{ session('message') }}</x-alert>
@endif
@if(session('error'))
    <x-alert type="danger">{{ session('error') }}</x-alert>
@endif
@if (session('temp_password'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="bi bi-key-fill me-2"></i>
        Mot de passe temporaire pour <strong>{{ session('temp_password_user') }}</strong> :
        <code class="fs-6">{{ session('temp_password') }}</code>
        <div class="small mt-1">Communiquez-le de manière sécurisée. L'utilisateur devra le changer à la prochaine connexion.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th class="d-none d-md-table-cell">Rôle</th>
                        <th class="d-none d-lg-table-cell">Service</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                            <td class="small">{{ $user->email }}</td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-university-secondary">{{ $user->role->label ?? '-' }}</span>
                            </td>
                            <td class="d-none d-lg-table-cell small">{{ $user->serviceRelation->service ?? '-' }}</td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Actif</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Désactivé</span>
                                @endif
                                @if ($user->must_reset_password)
                                    <span class="badge bg-warning text-dark" title="Doit réinitialiser son mot de passe">
                                        <i class="bi bi-key"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ((int) $user->role_id !== 1 && $user->id !== auth()->id())
                                    <div class="btn-group btn-group-sm">
                                        {{-- Réinitialiser identifiants --}}
                                        <button type="button" class="btn btn-outline-warning"
                                                data-bs-toggle="modal" data-bs-target="#resetModal{{ $user->id }}"
                                                title="Réinitialiser les identifiants">
                                            <i class="bi bi-key"></i>
                                        </button>
                                        {{-- Activer / désactiver --}}
                                        <button type="button" class="btn {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                data-bs-toggle="modal" data-bs-target="#toggleModal{{ $user->id }}"
                                                title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="bi bi-{{ $user->is_active ? 'person-x' : 'person-check' }}"></i>
                                        </button>
                                    </div>

                                    {{-- Modal réinitialisation --}}
                                    <div class="modal fade" id="resetModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-key me-2"></i>Réinitialiser les identifiants</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Générer un nouveau mot de passe temporaire pour
                                                    <strong>{{ $user->firstname }} {{ $user->lastname }}</strong> ({{ $user->email }}) ?
                                                    <br>L'utilisateur devra le changer à sa prochaine connexion.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('settings.users.reset', $user->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning">
                                                            <i class="bi bi-key me-1"></i>Réinitialiser
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal activation/désactivation --}}
                                    <div class="modal fade" id="toggleModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-person-gear me-2"></i>
                                                        {{ $user->is_active ? 'Désactiver' : 'Activer' }} le compte
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Confirmez-vous de <strong>{{ $user->is_active ? 'désactiver' : 'activer' }}</strong>
                                                    le compte de <strong>{{ $user->firstname }} {{ $user->lastname }}</strong> ?
                                                    @if ($user->is_active)
                                                        <div class="text-danger small mt-2">L'utilisateur ne pourra plus se connecter.</div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('settings.users.toggle', $user->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                                            {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection
