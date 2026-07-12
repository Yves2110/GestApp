@props([
    'id'     => 'modal',
    'title'  => '',
    'size'   => '',
    'icon'   => null,
    'type'   => 'default',
    'footer' => null,
])

@php
$sizeClass = match($size) {
    'sm'  => 'modal-sm',
    'lg'  => 'modal-lg',
    'xl'  => 'modal-xl',
    default => '',
};
$typeClass = $type !== 'default' ? "modal-{$type}" : '';
@endphp

<div class="modal fade {{ $typeClass }}" id="{{ $id }}" tabindex="-1"
     aria-labelledby="{{ $id }}-label" aria-hidden="true">
    <div class="modal-dialog {{ $sizeClass }} modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}-label">
                    @if($icon)
                        <i class="bi {{ $icon }} me-2"></i>
                    @endif
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
