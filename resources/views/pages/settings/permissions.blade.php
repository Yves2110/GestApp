@extends('layouts.app')

@section('title', 'Permissions des rôles - GestApp')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('settings') }}">Paramètres</a></li>
    <li class="breadcrumb-item active">Permissions</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Permissions des rôles</h1>
        <p class="page-subtitle">Attribuez les permissions à chaque rôle</p>
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
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<ul class="nav nav-tabs mb-3" role="tablist">
    @foreach ($roles as $index => $role)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#role{{ $role->id }}" type="button" role="tab">
                {{ $role->label }}
                @if ((int) $role->id === 1)
                    <i class="bi bi-star-fill text-warning ms-1" title="Toutes les permissions"></i>
                @endif
            </button>
        </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach ($roles as $index => $role)
        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="role{{ $role->id }}" role="tabpanel">
            @if ((int) $role->id === 1)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Le <strong>Super Administrateur</strong> dispose automatiquement de toutes les permissions. Ce rôle n'est pas modifiable.
                </div>
            @else
                <form action="{{ route('settings.permissions.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php $rolePermissionIds = $role->permissions->pluck('id')->toArray(); @endphp

                    <div class="row g-3">
                        @foreach ($permissions as $group => $groupPermissions)
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <strong>{{ $group ?? 'Autres' }}</strong>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input group-toggle" type="checkbox"
                                                   data-group="{{ \Illuminate\Support\Str::slug($group) }}-{{ $role->id }}">
                                            <label class="form-check-label small">Tout</label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($groupPermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input perm-{{ \Illuminate\Support\Str::slug($group) }}-{{ $role->id }}"
                                                       type="checkbox" name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       id="perm{{ $role->id }}_{{ $permission->id }}"
                                                       @checked(in_array($permission->id, $rolePermissionIds))>
                                                <label class="form-check-label" for="perm{{ $role->id }}_{{ $permission->id }}">
                                                    {{ $permission->label }}
                                                    <code class="small text-muted d-block">{{ $permission->name }}</code>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Enregistrer les permissions
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.group-toggle').forEach(function (toggle) {
    toggle.addEventListener('change', function () {
        const group = this.dataset.group;
        document.querySelectorAll('.perm-' + group).forEach(function (cb) {
            cb.checked = toggle.checked;
        });
    });
});
</script>
@endpush
