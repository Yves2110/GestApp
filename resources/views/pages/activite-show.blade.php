@extends('layouts.app')

@section('title', 'Détail — ' . \Illuminate\Support\Str::limit($activity->label, 40))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('Activites') }}">Activités</a></li>
    <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($activity->label, 40) }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $activity->label }}</h1>
        <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
            @php
                $wfClass = match($activity->workflow_status) {
                    'draft'     => 'badge-status--draft',
                    'pending'   => 'badge-status--pending',
                    'validated' => 'badge-status--active',
                    'rejected'  => 'badge-status--rejected',
                    default     => 'badge-secondary',
                };
            @endphp
            <span class="badge badge-status {{ $wfClass }}">{{ $activity->workflowLabel }}</span>
            @if($activity->service)
                <span class="badge badge-secondary"><i class="bi bi-building me-1"></i>{{ $activity->service->label }}</span>
            @endif
            @if($activity->periode)
                <span class="badge badge-secondary"><i class="bi bi-calendar3 me-1"></i>{{ $activity->periode->label }}</span>
            @endif
        </div>
    </div>
    <div class="page-actions">
        <a href="{{ route('activites.edit', $activity->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('Activites') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

@if($errors->has('workflow'))
    <x-alert type="danger" :dismissible="false">{{ $errors->first('workflow') }}</x-alert>
@endif

{{-- Workflow actions --}}
@php
    $roleId = Auth::user()->role_id;
    $ws     = $activity->workflow_status;
@endphp
<div class="card mb-6">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-diagram-2 me-2"></i>Workflow de validation</h5>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            {{-- Étapes visuelles --}}
            @foreach(['draft'=>'Brouillon','pending'=>'En attente','validated'=>'Validé'] as $step => $stepLabel)
            @php
                $isDone    = match($step) {
                    'draft'     => true,
                    'pending'   => in_array($ws, ['pending','validated','rejected']),
                    'validated' => $ws === 'validated',
                };
                $isActive  = $ws === $step || ($ws === 'rejected' && $step === 'pending');
                $stepColor = $isDone ? 'var(--clr-success)' : ($isActive ? 'var(--clr-primary)' : 'var(--clr-gray-300)');
            @endphp
            <div class="d-flex align-items-center gap-2">
                <div style="width:32px;height:32px;border-radius:50%;background:{{ $stepColor }};display:flex;align-items:center;justify-content:center;color:white;font-size:.75rem;font-weight:600;flex-shrink:0;">
                    @if($isDone && !$isActive)<i class="bi bi-check"></i>@else {{ $loop->iteration }}@endif
                </div>
                <span class="text-sm {{ $isActive ? 'fw-semi' : 'text-muted' }}">{{ $stepLabel }}</span>
                @if(!$loop->last)<div style="width:40px;height:2px;background:var(--clr-gray-200);"></div>@endif
            </div>
            @endforeach

            @if($ws === 'rejected')
                <span class="badge badge-status badge-status--rejected ms-2">Rejeté</span>
            @endif
        </div>

        @if($activity->rejection_reason && $ws === 'rejected')
            <div class="alert alert-danger mt-4 mb-0" style="border-radius:var(--border-radius);">
                <i class="bi bi-x-circle-fill"></i>
                <strong>Motif du rejet :</strong> {{ $activity->rejection_reason }}
            </div>
        @endif

        {{-- Boutons de transition --}}
        <div class="d-flex gap-3 flex-wrap mt-4">
            @if($ws === 'draft' && in_array($roleId, [1,3,4]))
                <form action="{{ route('activites.workflow', $activity->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="to_status" value="pending">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send"></i> Soumettre pour validation
                    </button>
                </form>
            @endif

            @if($ws === 'pending' && in_array($roleId, [1,3]))
                <form action="{{ route('activites.workflow', $activity->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="to_status" value="validated">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle"></i> Valider
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle"></i> Rejeter
                </button>
            @endif

            @if($ws === 'rejected')
                <form action="{{ route('activites.workflow', $activity->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="to_status" value="draft">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Remettre en brouillon
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Détails --}}
<div class="row g-4 mb-6">
    <div class="col-lg-8">
        <x-card title="Informations générales" icon="bi-info-circle">
            <div class="row g-4">
                <div class="col-sm-6">
                    <div class="text-xs text-muted mb-1">Objectif</div>
                    <div class="fw-medium">{{ $activity->objective->label ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-xs text-muted mb-1">Sous-objectif</div>
                    <div class="fw-medium">{{ $activity->underObjective->label ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-xs text-muted mb-1">Indicateur</div>
                    <div>{{ $activity->indicator ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-xs text-muted mb-1">Cible</div>
                    <div>{{ $activity->target ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-xs text-muted mb-1">Budget prévu</div>
                    <div class="fw-semi">{{ $activity->price ? number_format($activity->price, 0, ',', ' ') . ' FCFA' : '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-xs text-muted mb-1">Source de financement</div>
                    <div>{{ $activity->source_of_funding ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-xs text-muted mb-1">Structure responsable</div>
                    <div>{{ $activity->structure ?? '—' }}</div>
                </div>
                @if($activity->commentary)
                <div class="col-12">
                    <div class="text-xs text-muted mb-1">Commentaire</div>
                    <div class="text-sm">{{ $activity->commentary }}</div>
                </div>
                @endif
            </div>
        </x-card>
    </div>

    <div class="col-lg-4">
        <x-card title="Métadonnées" icon="bi-clock-history">
            <div class="d-flex flex-column gap-3">
                <div>
                    <div class="text-xs text-muted mb-1">Créée le</div>
                    <div class="text-sm">{{ $activity->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                @if($activity->submitted_at)
                <div>
                    <div class="text-xs text-muted mb-1">Soumise le</div>
                    <div class="text-sm">{{ $activity->submitted_at->format('d/m/Y à H:i') }}</div>
                    @if($activity->submittedBy)
                        <div class="text-xs text-muted">par {{ $activity->submittedBy->firstname }} {{ $activity->submittedBy->lastname }}</div>
                    @endif
                </div>
                @endif
                @if($activity->validated_at)
                <div>
                    <div class="text-xs text-muted mb-1">Validée le</div>
                    <div class="text-sm">{{ $activity->validated_at->format('d/m/Y à H:i') }}</div>
                    @if($activity->validatedBy)
                        <div class="text-xs text-muted">par {{ $activity->validatedBy->firstname }} {{ $activity->validatedBy->lastname }}</div>
                    @endif
                </div>
                @endif
            </div>
        </x-card>
    </div>
</div>

{{-- Historique des statuts --}}
@if($activity->statusHistory->isNotEmpty())
<x-card title="Journal des transitions" icon="bi-journal-text" class="mb-6">
    <div class="table-wrapper" style="border:none;box-shadow:none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>De</th>
                    <th>Vers</th>
                    <th>Par</th>
                    <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activity->statusHistory as $h)
                <tr>
                    <td class="text-sm">{{ $h->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="badge badge-secondary text-xs">{{ \App\Models\Activity::WORKFLOW_LABELS[$h->from_status] ?? $h->from_status }}</span></td>
                    <td><span class="badge badge-primary text-xs">{{ \App\Models\Activity::WORKFLOW_LABELS[$h->to_status] ?? $h->to_status }}</span></td>
                    <td class="text-sm">{{ $h->user->firstname ?? '—' }} {{ $h->user->lastname ?? '' }}</td>
                    <td class="text-sm text-muted">{{ $h->comment ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>
@endif

{{-- Modal rejection --}}
@if($activity->workflow_status === 'pending' && in_array(Auth::user()->role_id, [1,3]))
<x-modal id="rejectModal" title="Rejeter l'activité" icon="bi-x-circle" type="confirm">
    <form action="{{ route('activites.workflow', $activity->id) }}" method="POST">
        @csrf
        <input type="hidden" name="to_status" value="rejected">
        <div class="form-group">
            <label class="form-label">Motif du rejet <span class="required">*</span></label>
            <textarea name="rejection_reason" class="form-control" rows="3"
                      placeholder="Expliquez pourquoi cette activité est rejetée…" required></textarea>
        </div>
        <x-slot:footer>
            <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-x-circle"></i> Confirmer le rejet
            </button>
        </x-slot:footer>
    </form>
</x-modal>
@endif
@endsection
