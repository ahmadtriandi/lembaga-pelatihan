@if ($paginator->hasPages())
<nav class="pager" aria-label="Halaman">
  @if ($paginator->onFirstPage())<span aria-disabled="true">Sebelumnya</span>@else<a href="{{ $paginator->previousPageUrl() }}">Sebelumnya</a>@endif
  @foreach ($elements as $element)
    @if (is_string($element))<span>{{ $element }}</span>@endif
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())<span class="active" aria-current="page">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
      @endforeach
    @endif
  @endforeach
  @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">Berikutnya</a>@else<span aria-disabled="true">Berikutnya</span>@endif
</nav>
@endif
