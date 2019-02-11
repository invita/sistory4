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
                @include('fe.includes.entitySearchResults', ["entities" => $data["results"], "style" => "table"])
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