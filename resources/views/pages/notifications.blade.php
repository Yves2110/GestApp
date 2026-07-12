@extends('layouts.app')

@section('title', 'Notifications')

@section('breadcrumb')
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Notifications</h1>
        <p class="page-subtitle">{{ $unread }} non lue{{ $unread > 1 ? 's' : '' }}</p>
    </div>
    @if($unread > 0)
    <div class="page-actions">
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-check2-all"></i> Tout marquer comme lu
            </button>
        </form>
    </div>
    @endif
</div>

<x-card :noPad="true">
    @if($notifications->isEmpty())
        <x-empty-state icon="bi-bell" title="Aucune notification" message="Vous êtes à jour !" />
    @else
        <div class="list-group list-group-flush">
            @foreach($notifications as $notif)
            @php
                $iconMap = [
                    'activity_submitted' => ['icon' => 'bi-send',         'color' => 'var(--clr-primary)'],
                    'activity_validated' => ['icon' => 'bi-check-circle-fill', 'color' => 'var(--clr-success)'],
                    'activity_rejected'  => ['icon' => 'bi-x-circle-fill','color' => 'var(--clr-danger)'],
                    'activity_reopen'    => ['icon' => 'bi-arrow-counterclockwise', 'color' => 'var(--clr-warning)'],
                ];
                $iconData = $iconMap[$notif->type] ?? ['icon' => 'bi-bell', 'color' => 'var(--clr-gray-400)'];
            @endphp
            <div class="list-group-item px-4 py-3 {{ !$notif->isRead() ? 'bg-primary-light' : '' }}"
                 style="border-left: 3px solid {{ !$notif->isRead() ? 'var(--clr-primary)' : 'transparent' }};">
                <div class="d-flex align-items-start gap-3">
                    <div style="color:{{ $iconData['color'] }};font-size:1.25rem;flex-shrink:0;margin-top:2px;">
                        <i class="bi {{ $iconData['icon'] }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <span class="fw-semi text-sm {{ !$notif->isRead() ? '' : 'text-muted' }}">{{ $notif->title }}</span>
                            <span class="text-xs text-muted flex-shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-sm text-muted mt-1">{{ $notif->message }}</div>
                        @if($notif->action_url)
                            <a href="{{ $notif->action_url }}"
                               class="text-xs text-link mt-1 d-inline-block"
                               onclick="fetch('{{ route('notifications.read', $notif->id) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}})">
                                Voir l'activité <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                    @if(!$notif->isRead())
                        <div class="status-dot status-dot--active flex-shrink-0 mt-2"></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</x-card>
@endsection
