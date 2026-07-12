@extends('layouts.app')

@section('title', 'Modifier une Activité - GestApp')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('Activites') }}">Activités</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Modifier l'activité</h1>
        <p class="page-subtitle">{{ \Illuminate\Support\Str::limit($activity->label, 60) }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('Activites') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

@if($errors->any())
    <x-alert type="danger" :dismissible="false">
        <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </x-alert>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('activites.update', $activity->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label for="label" class="form-label">
                        <i class="bi bi-tag me-1"></i>Libellé de l'activité *
                    </label>
                    <input type="text" class="form-control" name="label" required
                           value="{{ old('label', $activity->label) }}" placeholder="Libellé de l'activité">
                </div>

                <div class="col-md-6">
                    <label for="objective_id" class="form-label">
                        <i class="bi bi-bullseye me-1"></i>Objectif Global *
                    </label>
                    <select class="form-select" name="objective_id" required>
                        <option value="" disabled>Choisir un objectif</option>
                        @foreach ($objectives as $objective)
                            <option value="{{ $objective->id }}" @selected(old('objective_id', $activity->objective_id) == $objective->id)>
                                {{ $objective->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="under_objective_id" class="form-label">
                        <i class="bi bi-diagram-3 me-1"></i>Sous-Objectif *
                    </label>
                    <select class="form-select" name="under_objective_id" required>
                        <option value="" disabled>Choisir un sous-objectif</option>
                        @foreach ($underobjectives as $underobjective)
                            <option value="{{ $underobjective->id }}" @selected(old('under_objective_id', $activity->under_objective_id) == $underobjective->id)>
                                {{ $underobjective->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="service_id" class="form-label">
                        <i class="bi bi-building me-1"></i>Service *
                    </label>
                    @if (auth()->check() && (int) auth()->user()->role_id === 4)
                        <select class="form-select" disabled>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" selected>{{ $service->service }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="service_id" value="{{ auth()->user()->service_id }}">
                        <small class="text-muted">Vous ne pouvez gérer que les activités de votre service.</small>
                    @else
                        <select class="form-select" name="service_id" required>
                            <option value="" disabled>Choisir un service</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id', $activity->service_id) == $service->id)>
                                    {{ $service->service }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="col-md-6">
                    <label for="periode_id" class="form-label">
                        <i class="bi bi-calendar3 me-1"></i>Période *
                    </label>
                    <select class="form-select" name="periode_id" required>
                        <option value="" disabled>Choisir une période</option>
                        @foreach ($trimestres as $trimestre)
                            <option value="{{ $trimestre->id }}" @selected(old('periode_id', $activity->periode_id) == $trimestre->id)>
                                {{ $trimestre->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="indicator" class="form-label">
                        <i class="bi bi-graph-up me-1"></i>Indicateur *
                    </label>
                    <input type="text" class="form-control" name="indicator" required
                           value="{{ old('indicator', $activity->indicator) }}" placeholder="Indicateur">
                </div>

                <div class="col-md-6">
                    <label for="target" class="form-label">
                        <i class="bi bi-bullseye me-1"></i>Cible *
                    </label>
                    <input type="text" class="form-control" name="target" required
                           value="{{ old('target', $activity->target) }}" placeholder="Cible">
                </div>

                <div class="col-md-6">
                    <label for="price" class="form-label">
                        <i class="bi bi-currency-euro me-1"></i>Coût (€) *
                    </label>
                    <input type="number" class="form-control" name="price" required
                           min="0" step="1" value="{{ old('price', $activity->price) }}" placeholder="0">
                </div>

                <div class="col-md-6">
                    <label for="source_of_funding" class="form-label">
                        <i class="bi bi-wallet2 me-1"></i>Source de financement *
                    </label>
                    <input type="text" class="form-control" name="source_of_funding" required
                           value="{{ old('source_of_funding', $activity->source_of_funding) }}" placeholder="Source de financement">
                </div>

                <div class="col-12">
                    <label for="structure" class="form-label">
                        <i class="bi bi-building me-1"></i>Structure responsable *
                    </label>
                    <input type="text" class="form-control" name="structure" required
                           value="{{ old('structure', $activity->structure) }}" placeholder="Structure responsable">
                </div>

                <div class="col-12">
                    <label for="commentary" class="form-label">
                        <i class="bi bi-chat-text me-1"></i>Commentaires
                    </label>
                    <textarea class="form-control" name="commentary" rows="3"
                              placeholder="Commentaires additionnels...">{{ old('commentary', $activity->commentary) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('Activites') }}" class="btn btn-ghost">
                    <i class="bi bi-x-circle me-1"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
