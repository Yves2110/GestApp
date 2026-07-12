@props([
    'icon'    => 'bi-inbox',
    'title'   => 'Aucune donnée',
    'message' => null,
    'action'  => null,
])

<div {{ $attributes->merge(['class' => 'table-empty py-5']) }}>
    <i class="bi {{ $icon }}"></i>
    <h6 class="mt-3 mb-1 fw-semi">{{ $title }}</h6>
    @if($message)
        <p class="text-muted text-sm mb-3">{{ $message }}</p>
    @endif
    @if($action)
        <div>{{ $action }}</div>
    @endif
</div>
