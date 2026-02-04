@if ($paginator->hasPages())

{{-- ===== TOP: Previous / Next ===== --}}
{{-- <div class="pagination-top" style="margin-bottom:8px; text-align:center; font-weight:600;">
    @if ($paginator->onFirstPage())
        <span style="opacity:0.4; margin-right:10px;">Previous</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="margin-right:10px; color:var(--primary);">Previous</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="margin-left:10px; color:var(--primary);">Next</a>
    @else
        <span style="opacity:0.4; margin-left:10px;">Next</span>
    @endif
</div> --}}


{{-- ===== CENTER PAGE NUMBERS (no < >) ===== --}}
<div class="pagination-numbers" style="text-align:center; margin-bottom:6px;">

    @foreach ($elements as $element)

        @if (is_string($element))
            <span class="dots" style="margin:0 4px;">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active" style="
                        background:var(--primary);
                        color:#fff;
                        padding:4px 8px;
                        border-radius:5px;
                        margin:0 3px;
                        font-weight:600;
                    ">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="
                        padding:4px 8px;
                        border-radius:5px;
                        margin:0 3px;
                        color:var(--primary-dark);
                        font-weight:600;
                    ">{{ $page }}</a>
                @endif
            @endforeach
        @endif

    @endforeach
</div>


{{-- ===== BOTTOM RESULTS ===== --}}
<div class="pagination-info" style="text-align:center; color:gray; font-size:13px;">
    Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }}
    of {{ $paginator->total() }} results
</div>

@endif

