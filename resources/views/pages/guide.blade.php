@extends('layouts.app')

@section('title', 'Guides')

@section('breadcrumb')
    <li class="breadcrumb-item active">Guides</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Guides d'évaluation</h1>
        <p class="page-subtitle">Documents de référence pour l'évaluation des activités</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGuideModal">
            <i class="bi bi-upload me-1"></i> Téléverser un guide
        </button>
    </div>
</div>

@if(session('message'))
    <x-alert type="success" :dismissible="true">{{ session('message') }}</x-alert>
@endif

<x-card :noPad="true">
    @if($guides->isEmpty())
        <x-empty-state icon="bi-file-earmark-text" title="Aucun guide" message="Téléversez le premier guide d'évaluation." />
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>Fichier</th>
                    <th class="hide-mobile">Mis en ligne</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guides as $guide)
                <tr>
                    <td class="text-sm text-muted">{{ $guide->id }}</td>
                    <td>
                        <a href="{{ asset('docs/' . $guide->fichier) }}" target="_blank"
                           class="d-flex align-items-center gap-2 text-link fw-medium">
                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                            {{ $guide->fichier }}
                        </a>
                    </td>
                    <td class="text-sm text-muted hide-mobile">{{ $guide->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('delete.guide', $guide->id) }}" class="btn btn-ghost btn-icon btn-sm text-danger" title="Supprimer"
                           onclick="return confirm('Supprimer ce guide ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-card>

{{-- Modal upload --}}
<x-modal id="addGuideModal" title="Téléverser un guide" icon="bi-upload">
    <form action="{{ route('guide') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label">Fichier PDF <span class="required">*</span></label>
            <input type="file" class="form-control" name="fichier" accept=".pdf,.doc,.docx" required>
            <small class="form-text text-muted">Formats acceptés : PDF, DOC, DOCX</small>
        </div>
        <x-slot:footer>
            <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-upload me-1"></i> Téléverser
            </button>
        </x-slot:footer>
    </form>
</x-modal>
@endsection
