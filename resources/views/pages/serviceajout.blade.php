@extends('layouts.app')

@section('title', 'Services')

@section('breadcrumb')
    <li class="breadcrumb-item active">Services</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Services</h1>
        <p class="page-subtitle">Gestion des services et membres</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-ghost btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
            <i class="bi bi-building me-1"></i> Nouveau service
        </button>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Ajouter un membre
        </a>
    </div>
</div>

@if(session('message'))
    <x-alert type="success" :dismissible="true">{{ session('message') }}</x-alert>
@endif

<x-empty-state icon="bi-building" title="Liste des services" message="Utilisez le bouton ci-dessus pour ajouter un nouveau service." />

{{-- Modal ajout service --}}
<x-modal id="addServiceModal" title="Nouveau service" icon="bi-building">
    <form action="{{ route('ajoutservice') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nom du service <span class="required">*</span></label>
            <input type="text" class="form-control" name="service" required placeholder="ex: Direction des Ressources Humaines">
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
