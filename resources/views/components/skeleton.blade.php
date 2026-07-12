@props([
    'rows'   => 5,
    'cols'   => 4,
    'height' => '2.5rem',
])

@php
$styleRow = "height:{$height};background:linear-gradient(90deg,#f0f0f0 25%,#e8e8e8 50%,#f0f0f0 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:4px;margin-bottom:.5rem;";
@endphp

<style>
@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

@for($r = 0; $r < $rows; $r++)
    <div style="{{ $styleRow }} opacity: {{ 1 - $r * (0.15) }};"></div>
@endfor
