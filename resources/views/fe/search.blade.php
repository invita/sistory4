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
                        {{ __("fe.advSearch_title") }}
                    </h4>
                    <form action="/advanced-search">
                        <div class="advancedSearchFields"></div>
                        @if (isset($layoutData["hdl"]) && $layoutData["hdl"])
                            <div class="flexRow advCollectionSearchIndicatorWrap">
                                <input type="checkbox" name="hdl" value="{{ $layoutData["hdl"] }}" id="advSearchInsideCurrent" {{ $layoutData["hdl"] ? "checked" : "" }}/>
                                <label id="advCollectionSearchIndicator" for="advSearchInsideCurrent">{{ __("fe.search_textInCollection") }} {{ first($layoutData["hdlTitle"]) }}</label>
                            </div>
                        @endif
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

        @elseif (isset($advQ) && count($advQ) && !count($data["results"]))
            <div class="medium-12 columns">
                <div class="searchNoResultsWrapper">
                    {{ __("fe.advSearch_noResults") }}
                </div>
            </div>
        @else
            <div class="medium-12 columns">
                <div class="searchNoResultsWrapper">
                    {{ __("fe.advSearch_emptyFirstSearch") }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection