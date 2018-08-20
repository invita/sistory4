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
        @if (count($data["results"]) > 0)

            <div class="medium-12 columns">
                <?php echo $paginatorTop; ?>
            </div>

            <div class="medium-12 columns">
                <div class="searchResults">
                    @foreach ($data["results"] as $result)
                        <div class="searchResult">
                            <a href="/details/{{ $result["handle_id"] }}">
                                <div class="imageWrapper">
                                    <img src="/img/structType/{{ $result["struct_type"] }}.png" />
                                </div>

                                @if ($result["struct_type"] == "entity")
                                    <div class="dataWrapper entity">
                                        @if ($result["html_dc_title"])
                                            <div class="title"><?php echo $result["html_dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["html_dc_creator"]; ?></div>
                                        @endif
                                    </div>
                                @elseif ($result["struct_type"] == "collection")
                                    <div class="dataWrapper collection">
                                        @if ($result["dc_title"])
                                            <div class="title"><?php echo $result["html_dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["html_dc_creator"]; ?></div>
                                        @endif
                                    </div>
                                @elseif ($result["struct_type"] == "file")
                                    <div class="dataWrapper file">
                                        @if ($result["dc_title"])
                                            <div class="title"><?php echo $result["html_dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["html_dc_creator"]; ?></div>
                                        @endif
                                        @if ($result["fileName"])
                                            <div class="fileName"><?php echo $result["fileName"]; ?></div>
                                        @endif
                                    </div>
                                @else
                                    <div class="dataWrapper unknown">
                                        @if ($result["dc_title"])
                                            <div class="title"><?php echo $result["html_dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["html_dc_creator"]; ?></div>
                                        @endif
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