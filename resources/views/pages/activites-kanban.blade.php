@extends('layouts.app')

@section('title', 'Kanban — Activités')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('Activites') }}">Activités</a></li>
    <li class="breadcrumb-item active">Kanban</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Vue Kanban</h1>
        <p class="page-subtitle">Suivi du workflow de validation</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('Activites') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-list-ul"></i>
            <span class="hide-mobile">Vue liste</span>
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
            <i class="bi bi-plus-lg"></i> Nouvelle activité
        </button>
    </div>
</div>

@php
$columns = [
    'draft'     => ['label' => 'Brouillon',  'icon' => 'bi-pencil-square',      'color' => 'var(--clr-gray-400)',    'bg' => 'var(--clr-gray-50)'],
    'pending'   => ['label' => 'En attente', 'icon' => 'bi-hourglass-split',    'color' => 'var(--clr-warning)',     'bg' => '#fffbeb'],
    'validated' => ['label' => 'Validé',     'icon' => 'bi-check-circle-fill',  'color' => 'var(--clr-success)',     'bg' => '#f0fdf4'],
    'rejected'  => ['label' => 'Rejeté',     'icon' => 'bi-x-circle-fill',      'color' => 'var(--clr-danger)',      'bg' => '#fef2f2'],
];
@endphp

<div class="kanban-board">
    @foreach($columns as $status => $col)
    <div class="kanban-column" id="col-{{ $status }}" data-status="{{ $status }}">
        <div class="kanban-column-header" style="border-top: 3px solid {{ $col['color'] }};">
            <div class="d-flex align-items-center gap-2">
                <i class="bi {{ $col['icon'] }}" style="color:{{ $col['color'] }};"></i>
                <span class="fw-semi">{{ $col['label'] }}</span>
                <span class="badge-count ms-1" id="count-{{ $status }}">
                    {{ $activities->where('workflow_status', $status)->count() }}
                </span>
            </div>
        </div>
        <div class="kanban-cards" id="cards-{{ $status }}">
            @forelse($activities->where('workflow_status', $status) as $activity)
            <div class="kanban-card" data-id="{{ $activity->id }}" data-status="{{ $status }}">
                <div class="kanban-card-title">
                    <a href="{{ route('activites.show', $activity->id) }}" class="fw-medium text-sm">
                        {{ \Illuminate\Support\Str::limit($activity->label, 60) }}
                    </a>
                </div>
                <div class="kanban-card-meta">
                    @if($activity->service)
                        <span class="badge badge-secondary text-xs">{{ $activity->service->label }}</span>
                    @endif
                    @if($activity->periode)
                        <span class="text-xs text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $activity->periode->label }}</span>
                    @endif
                    @if($activity->price)
                        <span class="text-xs text-muted"><i class="bi bi-cash me-1"></i>{{ number_format($activity->price, 0, ',', ' ') }}</span>
                    @endif
                </div>
                <div class="kanban-card-actions">
                    <a href="{{ route('activites.show', $activity->id) }}" class="btn btn-ghost btn-icon" style="font-size:.75rem;">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    @php $transitions = \App\Models\Activity::WORKFLOW_TRANSITIONS[$status] ?? []; @endphp
                    @foreach($transitions as $to)
                    @php
                        $btnClass = match($to) {
                            'validated' => 'btn-success',
                            'rejected'  => 'btn-danger',
                            'pending'   => 'btn-primary',
                            'draft'     => 'btn-outline-secondary',
                            default     => 'btn-outline-primary',
                        };
                        $toLabel = \App\Models\Activity::WORKFLOW_LABELS[$to];
                    @endphp
                    @php
                        $canTransition = match($to) {
                            'pending'   => in_array(Auth::user()->role_id, [1,3,4]),
                            'validated' => in_array(Auth::user()->role_id, [1,3]),
                            'rejected'  => in_array(Auth::user()->role_id, [1,3]),
                            'draft'     => true,
                            default     => false,
                        };
                    @endphp
                    @if($canTransition)
                    <form action="{{ route('activites.workflow', $activity->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="to_status" value="{{ $to }}">
                        <button type="submit" class="btn {{ $btnClass }} btn-sm" style="font-size:.7rem;padding:.2rem .5rem;"
                                title="→ {{ $toLabel }}">
                            {{ $toLabel }}
                        </button>
                    </form>
                    @endif
                    @endforeach
                </div>
            </div>
            @empty
            <div class="kanban-empty">
                <i class="bi bi-inbox text-muted"></i>
                <span class="text-xs text-muted">Aucune activité</span>
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
// Scroll horizontal kanban sur mobile avec souris
const board = document.querySelector('.kanban-board');
if (board) {
    let isDown = false, startX, scrollLeft;
    board.addEventListener('mousedown', e => { isDown = true; startX = e.pageX - board.offsetLeft; scrollLeft = board.scrollLeft; });
    board.addEventListener('mouseleave', () => isDown = false);
    board.addEventListener('mouseup', () => isDown = false);
    board.addEventListener('mousemove', e => {
        if (!isDown) return;
        e.preventDefault();
        board.scrollLeft = scrollLeft - (e.pageX - board.offsetLeft - startX);
    });
}
</script>
@endpush
