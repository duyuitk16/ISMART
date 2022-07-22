<?php
// config
$link_limit = 5; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>


@if ($paginator->lastPage() > 1)
    <ul class="pagination">
        {{-- Chuyển về đầu --}}
        {{-- <li class="page-item {{ $paginator->currentPage() == 1 ? ' disabled' : '' }}">
            <a href="{{ $paginator->url(1) }}" class="page-link">First</a>
        </li> --}}

        {{-- Chuyển từ từ --}}

        @if ($paginator->currentPage() > 1)
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-angle-double-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                        <span><i class="fa fa-angle-double-left"></i></span>
                    </a>
                </li>
            @endif
        @endif



        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) {
                $to += $half_total_links - $paginator->currentPage();
            }
            if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
            ?>
            @if ($from < $i && $i < $to)
                <li class="page-item {{ $paginator->currentPage() == $i ? ' active' : '' }}">
                    <a href="{{ $paginator->url($i) }}" class="page-link">{{ $i }}</a>
                </li>
            @endif
        @endfor


        {{-- Chuyển về cuối --}}
        {{-- <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-link">Last</a>
        </li> --}}


        {{-- Chuyển từ từ --}}
        @if ($paginator->currentPage() < $paginator->lastPage())
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                        <span><i class="fa fa-angle-double-right"></i></span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-angle-double-right"></i></span>
                </li>
            @endif
        @endif

    </ul>
@endif
