@props([
    'breadcrumbs' => [],
    'titulo' => ''
])
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    @foreach ($breadcrumbs as $item)
                        @if ($item['url'] == 'null')
                            <li class="breadcrumb-item"><a href="javascript: void(0)">{{ $item['title'] }}</a></li>
                        @elseif ($item['url'] == '')
                            <li class="breadcrumb-item" aria-current="page">{{ $item['title'] }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">
                        {{ $titulo }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>
