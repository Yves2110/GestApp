@props([
    'type'        => 'info',
    'dismissible' => true,
    'icon'        => null,
])

@php
$icons = [
    'success' => 'bi-check-circle-fill',
    'danger'  => 'bi-exclamation-triangle-fill',
    'warning' => 'bi-exclamation-circle-fill',
    'info'    => 'bi-info-circle-fill',
];
$iconClass = $icon ?? ($icons[$type] ?? 'bi-info-circle-fill');
@endphp

<div {{ $attributes->merge(['class' => "alert alert-{$type}" . ($dismissible ? ' alert-dismissible fade show' : '')]) }}
     role="alert">
    <i class="bi {{ $iconClass }}"></i>
    {{ $slot }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    @endif
</div>
