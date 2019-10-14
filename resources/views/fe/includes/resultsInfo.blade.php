<div class="resultsInfo">
    <!--
    ResultsInfo
    childStyle: {{ $childStyle }}
    took: {{ $took }}
    totalHits: {{ $totalHits }}
    -->

    <div class="buttons">
        <div class="btn {{ $childStyle == "table" ? "active" : "" }}">
            <a href="{{ si4link("", [ "layout" => "table" ]) }}">
                <img src="/images/resultTable.png" />
            </a>
        </div>
        <div class="btn {{ $childStyle == "cards" ? "active" : "" }}">
            <a href="{{ si4link("", [ "layout" => "cards" ]) }}">
                <img src="/images/resultCards.png" />
            </a>
        </div>
    </div>
    <div class="infoText1">
        {{ __("fe.hit_count") }}: {{ $totalHits }}
    </div>
    <div class="infoText2">
        {{ __("fe.request_duration") }}: {{ $took }}ms
    </div>


</div>