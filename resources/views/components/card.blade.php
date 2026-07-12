@props([
    'title'   => null,
    'icon'    => null,
    'actions' => null,
    'footer'  => null,
    'noPad'   => false,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($title)
        <div class="card-header">
            <h5 class="card-title">
                @if($icon)
                    <i class="bi {{ $icon }} me-2 text-muted"></i>
                @endif
                {{ $title }}
            </h5>
            @if($actions)
                <div class="card-header-actions">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div @class(['card-body', 'p-0' => $noPad])>
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
