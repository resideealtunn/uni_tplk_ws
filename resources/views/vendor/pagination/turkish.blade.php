@if ($paginator->hasPages())
    <nav class="custom-pagination" aria-label="Sayfalandırma">
        <ul>
            {{-- Önceki Sayfa --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>Önceki</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Önceki</a></li>
            @endif

            {{-- Sayfa Numaraları --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Sonraki Sayfa --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Sonraki</a></li>
            @else
                <li class="disabled"><span>Sonraki</span></li>
            @endif
        </ul>
    </nav>
@endif 