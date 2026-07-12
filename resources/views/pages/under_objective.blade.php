@extends('layouts.app')

@section('title', 'Sous-objectifs')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('Objective') }}">Objectifs</a></li>
    <li class="breadcrumb-item active">Sous-objectifs</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Sous-objectifs</h1>
        <p class="page-subtitle">Déclinaison des objectifs stratégiques</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUnderObjectiveModal">
            <i class="bi bi-plus-lg me-1"></i> Nouveau sous-objectif
        </button>
    </div>
</div>

@if(session('message'))
    <x-alert type="success" :dismissible="true">{{ session('message') }}</x-alert>
@endif

<x-card :noPad="true">
    @if($under_objectives->isEmpty())
        <x-empty-state icon="bi-diagram-3" title="Aucun sous-objectif" message="Créez votre premier sous-objectif." />
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px">N°</th>
                    <th>Objectif parent</th>
                    <th>Intitulé</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($under_objectives as $uo)
                <tr>
                    <td class="text-sm text-muted fw-semi">{{ $uo->id }}</td>
                    <td class="text-sm text-muted">{{ $uo->objective->label ?? '—' }}</td>
                    <td class="fw-medium">{{ $uo->label }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('delete.under_objective', $uo->id) }}" class="btn btn-ghost btn-icon btn-sm text-danger" title="Supprimer"
                               onclick="return confirm('Supprimer ce sous-objectif ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-card>

{{-- Modal ajout --}}
<x-modal id="addUnderObjectiveModal" title="Nouveau sous-objectif" icon="bi-diagram-3">
    <form action="{{ route('UnderObjectiveStore') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Intitulé <span class="required">*</span></label>
            <input type="text" class="form-control" name="label" required placeholder="Libellé du sous-objectif">
        </div>
        <div class="form-group">
            <label class="form-label">Objectif parent <span class="required">*</span></label>
            <select name="objective_id" class="form-select" required>
                <option value="" disabled selected>Choisir un objectif</option>
                @foreach($objectives as $objective)
                    <option value="{{ $objective->id }}">{{ $objective->label }}</option>
                @endforeach
            </select>
        </div>
        <x-slot:footer>
            <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-check-lg me-1"></i> Enregistrer
            </button>
        </x-slot:footer>
    </form>
</x-modal>
@endsection
