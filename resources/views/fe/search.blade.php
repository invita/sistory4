@extends("layout")

@section("head")
@endsection

@section("body")
<div class="content">

    @if ($searchType == "advanced-search")
        <div class="advancedSearch">
            <div class="row">
                <div class="medium-12 columns">
                    <h4>
                        Advanced search
                    </h4>
                    <form action="/advanced-search">
                        <div class="advancedSearchFields"></div>
                    </form>
                </div>
            </div>

        </div>
    @endif

    <div class="row">
        <?php
            //echo "<pre>"; print_r(array_keys($data)); echo "</pre>";
            //echo "<pre>"; print_r($data["results"][0]); echo "</pre>";
        ?>

        @if (count($data["results"]) > 0)

            <div class="medium-12 columns">
                <?php echo $paginatorTop; ?>
            </div>

            <div class="medium-12 columns">
                <div class="searchResults">
                    @foreach ($data["results"] as $result)
                        <div class="searchResult">
                            <a href="/details/{{ $result["system"]["handle_id"] }}">
                                <div class="imageWrapper">
                                    <img src="{{ $result["thumb"] }}" />
                                </div>

                                @if ($result["system"]["struct_type"] == "entity")
                                    <div class="dataWrapper entity">
                                        @if (isset($result["si4"]["title"]))
                                            <div class="value title">{{ first($result["si4"]["title"]) }}</div>
                                        @endif
                                        @if (isset($result["si4"]["creator"]))
                                            <div class="value creator">{{ join("; ", $result["si4"]["creator"]) }}</div>
                                        @endif
                                    </div>
                                @elseif ($result["system"]["struct_type"] == "collection")
                                    <div class="dataWrapper collection">
                                        @if (isset($result["si4"]["title"]))
                                            <div class="value title">{{ first($result["si4"]["title"]) }}</div>
                                        @endif
                                        @if (isset($result["si4"]["creator"]))
                                            <div class="value creator">{{ join("; ", $result["si4"]["creator"]) }}</div>
                                        @endif
                                    </div>
                                @elseif ($result["system"]["struct_type"] == "file")
                                    <div class="dataWrapper file">
                                        @if (isset($result["si4"]["title"]))
                                            <div class="value title">{{ first($result["si4"]["title"]) }}</div>
                                        @endif
                                        @if (isset($result["si4"]["creator"]))
                                            <div class="value creator">{{ join("; ", $result["si4"]["creator"]) }}</div>
                                        @endif
                                        @if ($result["file"]["fileName"])
                                            <div class="value fileName">{{ $result["file"]["fileName"] }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="dataWrapper unknown">
                                        @if (isset($result["si4"]["title"]))
                                            <div class="value title">{{ first($result["si4"]["title"]) }}</div>
                                        @endif
                                        @if (isset($result["si4"]["creator"]))
                                            <div class="value creator">{{ join("; ", $result["si4"]["creator"]) }}</div>
                                        @endif
                                    </div>
                                @endif

                                @if (isset($result["file"]) && isset($result["file"]["fullTextHits"]))
                                    <div class="dataWrapper">
                                        <div class="fullTextHits">
                                            @foreach($result["file"]["fullTextHits"] as $fullTextHit)
                                                <div class="value fullTextHit"><?php echo $fullTextHit; ?></div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="medium-12 columns">
                <?php echo $paginatorBot; ?>
            </div>

        @elseif ($layoutData["q"] && !count($data["results"]))
            <div class="medium-12 columns">
                <div class="searchNoResultsWrapper">
                    No results...
                </div>
            </div>
        @else
            <div class="medium-12 columns">
                <div class="searchNoResultsWrapper">
                    Si4 Search
                </div>
            </div>
        @endif
    </div>

</div>
@endsection