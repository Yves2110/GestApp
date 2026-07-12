@props([
    'variant' => 'primary',
    'size'    => '',
    'icon'    => null,
    'type'    => 'button',
    'href'    => null,
    'block'   => false,
])

@php
$classes = "btn btn-{$variant}";
if ($size)  $classes .= " btn-{$size}";
if ($block) $classes .= ' btn-block';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="bi {{ $icon }}"></i>@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="bi {{ $icon }}"></i>@endif
        {{ $slot }}
    </button>
@endif
