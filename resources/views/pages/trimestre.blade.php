@extends('layouts.app')

@section('title', 'Périodes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Périodes</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Périodes</h1>
        <p class="page-subtitle">Gestion des trimestres et périodes d'activité</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPeriodeModal">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle période
        </button>
    </div>
</div>

@if(session('message'))
    <x-alert type="success" :dismissible="true">{{ session('message') }}</x-alert>
@endif

<x-card :noPad="true">
    @if($trimestres->isEmpty())
        <x-empty-state icon="bi-calendar3" title="Aucune période" message="Créez votre première période." />
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px">N°</th>
                    <th>Intitulé</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trimestres as $t)
                <tr>
                    <td class="text-sm text-muted fw-semi">{{ $t->id }}</td>
                    <td class="fw-medium">{{ $t->label }}</td>
                    <td>
                        <span class="text-xs text-muted">Aucune action</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-card>

{{-- Modal ajout --}}
<x-modal id="addPeriodeModal" title="Nouvelle période" icon="bi-calendar3">
    <form action="{{ route('trimestre.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Intitulé <span class="required">*</span></label>
            <input type="text" class="form-control" name="label" required placeholder="ex: Trimestre 1, Semestre 2...">
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
