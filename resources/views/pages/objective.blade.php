@extends('layouts.app')

@section('title', 'Objectifs')

@section('breadcrumb')
    <li class="breadcrumb-item active">Objectifs</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Objectifs</h1>
        <p class="page-subtitle">Gestion des objectifs stratégiques</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addObjectiveModal">
            <i class="bi bi-plus-lg me-1"></i> Nouvel objectif
        </button>
    </div>
</div>

@if(session('message'))
    <x-alert type="success" :dismissible="true">{{ session('message') }}</x-alert>
@endif

<x-card :noPad="true">
    @if($objectives->isEmpty())
        <x-empty-state icon="bi-bullseye" title="Aucun objectif" message="Créez votre premier objectif stratégique." />
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px">N°</th>
                    <th>Intitulé</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($objectives as $objective)
                <tr>
                    <td class="text-sm text-muted fw-semi">{{ $objective->id }}</td>
                    <td class="fw-medium">{{ $objective->label }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('edit.objective', $objective->id) }}" class="btn btn-ghost btn-icon btn-sm" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('delete.objective', $objective->id) }}" class="btn btn-ghost btn-icon btn-sm text-danger" title="Supprimer"
                               onclick="return confirm('Supprimer cet objectif ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3">
        {{ $objectives->links('vendor.pagination.custom') }}
    </div>
    @endif
</x-card>

{{-- Modal ajout --}}
<x-modal id="addObjectiveModal" title="Nouvel objectif" icon="bi-bullseye">
    <form action="{{ route('ObjectiveStore') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Intitulé <span class="required">*</span></label>
            <input type="text" class="form-control" name="label" required placeholder="Libellé de l'objectif">
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
