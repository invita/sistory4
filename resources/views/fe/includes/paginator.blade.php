<?php
    $firstPage = 1;
    $lastPage = ceil($totalHits / $limit);
    $curPage = floor($offset / $limit) +1;

    $firstOffset = 0;
    $lastOffset = ($lastPage -1) * $limit;

    $nextOffset = ($curPage) * $limit;
    $prevOffset = ($curPage -2) * $limit;

    if ($prevOffset < $firstOffset) $prevOffset = $firstOffset;
    if ($nextOffset > $lastOffset) $nextOffset = $lastOffset;

    $midPagesCount = 3;

    $prevMidPages = [];
    for ($i = $midPagesCount; $i >= 1; $i--) {
        $mp = $curPage - $i;
        if ($mp <= 1) continue;
        $prevMidPages[] = [
            "page" => $mp,
            "offset" => ($mp -1) * $limit,
        ];
    }

    $nextMidPages = [];
    for ($i = 1; $i <= $midPagesCount; $i++) {
        $mp = $curPage + $i;
        if ($mp >= $lastPage) continue;
        $nextMidPages[] = [
            "page" => $mp,
            "offset" => ($mp -1) * $limit,
        ];
    }

    $hasMorePrevMidPages = $curPage > $midPagesCount +2;
    $hasMoreNextMidPages = $curPage < $lastPage - $midPagesCount -1;

    //if ($nextOffset > $totalHits/$limit
    si4config()
?>

<!--
<div>
    Paginator<br>
    Offset: {{ $offset }}<br>
    Limit: {{ $limit }}<br>
    TotalHits: {{ $totalHits }}<br>
    CurPage: {{ $curPage }}<br>
    LastPage: {{ $lastPage }}<br>
    <hr>
    PrevOffset: {{ $prevOffset }}<br>
    NextOffset: {{ $nextOffset }}<br>
</div>
-->

<div>
    <!--
    <span>Paging: </span>
    -->

    <div class="childPagination">

        <!-- Prev -->
        @if ($prevOffset == $offset)
            <span class="pagLink">{{ __("fe.pagination_prev") }}</span>
        @else
            <a class="pagLink" href="{{ si4link("", [ "offset" => $prevOffset ]) }}">{{ __("fe.pagination_prev") }}</a>
        @endif

        <!-- First -->
        @if ($curPage > $firstPage)
            <a class="pagLink" href="{{ si4link("", [ "offset" => $firstOffset ]) }}">{{ $firstPage }}</a>
        @endif

        @if ($hasMorePrevMidPages)
            <span>...</span>
        @endif

        <!-- Prev mid -->
        @foreach ($prevMidPages as $p)
            <a class="pagLink" href="{{ si4link("", [ "offset" => $p["offset"] ]) }}">{{ $p["page"] }}</a>
        @endforeach

        <!-- Current page -->
        <span class="pagLink currentPage">{{ $curPage }}</span>

        <!-- Next mid -->
        @foreach ($nextMidPages as $p)
            <a class="pagLink" href="{{ si4link("", [ "offset" => $p["offset"] ]) }}">{{ $p["page"] }}</a>
        @endforeach

        @if ($hasMoreNextMidPages)
            <span>...</span>
        @endif

        <!-- Last -->
        @if ($curPage < $lastPage)
            <a class="pagLink" href="{{ si4link("", [ "offset" => $lastOffset ]) }}">{{ $lastPage }}</a>
        @endif

        <!-- Next -->
        @if ($nextOffset == $offset)
            <span class="pagLink">{{ __("fe.pagination_next") }}</span>
        @else
            <a class="pagLink" href="{{ si4link("", [ "offset" => $nextOffset ]) }}">{{ __("fe.pagination_next") }}</a>
        @endif

    </div>

</div>
