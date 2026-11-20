<ul class="glass-pagination flex items-center gap-1 list-none m-0 p-0">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
    <li><span class="glass-page-item disabled">&laquo;</span></li>
    @else
    <li><a class="glass-page-item" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
    @endif

    <!-- Pagination Elements -->
    @foreach ($elements as $element)
    <!-- "Three Dots" Separator -->
    @if (is_string($element))
    <li><span class="glass-page-item disabled">{{ $element }}</span></li>
    @endif

    <!-- Array Of Links -->
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li><span class="glass-page-item active">{{ $page }}</span></li>
    @else
    <li><a class="glass-page-item" href="{{ $url }}">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
    <li><a class="glass-page-item" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
    @else
    <li><span class="glass-page-item disabled">&raquo;</span></li>
    @endif
</ul>
