@extends("layout")

@section("head")
@endsection

@section("body")
<div class="content">

    <div class="row">
        <div class="medium-12 columns">
            <div class="searchFormContainer">
                <form>
                    <input id="searchInput" class="query" type="text" name="q"
                        value="{{$q}}" placeholder="Search..." autocomplete="off" />
                    <input class="submit" type="submit" value="Search">
                </form>
            </div>
        </div>
        @if ($q && count($data["results"]) > 0)
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
                                        @if ($result["dc_title"])
                                            <div class="title"><?php echo $result["dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["dc_creator"]; ?></div>
                                        @endif
                                    </div>
                                @elseif ($result["struct_type"] == "collection")
                                    <div class="dataWrapper collection">
                                        @if ($result["dc_title"])
                                            <div class="title"><?php echo $result["dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["dc_creator"]; ?></div>
                                        @endif
                                    </div>
                                @elseif ($result["struct_type"] == "file")
                                    <div class="dataWrapper file">
                                        @if ($result["dc_title"])
                                            <div class="title"><?php echo $result["dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["dc_creator"]; ?></div>
                                        @endif
                                        @if ($result["fileName"])
                                            <div class="fileName"><?php echo $result["fileName"]; ?></div>
                                        @endif
                                    </div>
                                @else
                                    <div class="dataWrapper unknown">
                                        @if ($result["dc_title"])
                                            <div class="title"><?php echo $result["dc_title"]; ?></div>
                                        @endif
                                        @if ($result["dc_creator"])
                                            <div class="creator"><?php echo $result["dc_creator"]; ?></div>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif ($q && !count($data["results"]))
            <div class="medium-12 columns">
                <div class="searchNoResultsWrapper">
                    Iskanje ni obrodilo sadov...
                </div>
            </div>
        @else
            <div class="medium-12 columns">
                <div class="searchNoResultsWrapper">
                    Sistory 4 Iskanje
                </div>
            </div>
        @endif
    </div>

</div>
@endsection