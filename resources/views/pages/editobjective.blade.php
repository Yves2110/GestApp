@extends('layouts.app')

@section('title', 'Modifier l\'objectif')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('Objective') }}">Objectifs</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Modifier l'objectif</h1>
        <p class="page-subtitle">{{ \Illuminate\Support\Str::limit($objective->label, 60) }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('Objective') }}" class="btn btn-ghost btn-sm">
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

<x-card title="Informations" icon="bi-bullseye" style="max-width:600px;">
    <form action="{{ route('ObjectiveUpdate', $objective->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Intitulé <span class="required">*</span></label>
            <input type="text" class="form-control" name="label" required
                   value="{{ old('label', $objective->label) }}">
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Enregistrer
            </button>
            <a href="{{ route('Objective') }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</x-card>
@endsection