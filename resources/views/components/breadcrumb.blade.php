@props(['items' => []])

@section('breadcrumb')
    @foreach($items as $item)
        @if(!$loop->last)
            <li class="breadcrumb-item">
                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            </li>
        @else
            <li class="breadcrumb-item active">{{ $item['label'] }}</li>
        @endif
    @endforeach
@endsection
