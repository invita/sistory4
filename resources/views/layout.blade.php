<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" xml:lang="{{ config('app.locale') }}" xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistory 4</title>

    <link href="/css/app.css" rel="stylesheet" type="text/css">
    <link href="/css/fe.css" rel="stylesheet" type="text/css">
    <script src="/js/app.js"></script>

    <link rel="icon" type="image/png" href="/img/icon.png">

@yield("head")

</head>

<body>
    @if (isset($layoutData) && isset($layoutData["jsData"]))
        <div id="jsData" class="displayNone"><?php echo $layoutData["jsData"]; ?></div>
    @endif

    @if (isset($layoutData) && isset($layoutData["topMenuHtml"]))
        <div class="topMenuWrap">
            <div class="row">
                <div class="medium-12 columns topMenuHolder">
                    <nav class="topMenu">
                        <?php echo  $layoutData["topMenuHtml"]; ?>
                    </nav>
                    <div id="langSelect">
                        <select>
                            @foreach ($layoutData["langauges"] as $langVal => $langText)
                                <option value="{{$langVal}}"{{ $langVal === $layoutData["lang"] ? " selected" : ""}}>{{$langText}}</option>
                            @endforeach
                        </select>
                        <script>$("#langSelect select").change(function(){ location.href = "/lang/"+$(this).val(); })</script>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="medium-12 columns">
            <div class="searchFormContainer">
                <div class="row">
                    <!-- Logo -->
                    <div class="medium-4 columns logo">
                        <a href="/">
                            <img src="/img/logo5.png" style="height:70px;" />
                        </a>
                    </div>

                    <div class="medium-8 columns mt-1">
                        <div class="textAlignRight mb-025">
                            <a class="default" href="/advanced-search">{{ __("fe.search_advancedSearch") }}</a>
                        </div>

                        <!-- Search Form -->
                        <form action="/search" class="flexRow" id="searchForm">
                            @if ($layoutData["allowInsideSearch"])
                                <input type="checkbox" name="hdl" value="{{ $layoutData["hdl"] }}" class="displayNone" id="searchInsideCurrent" {{ $layoutData["hdl"] ? "checked" : "" }}/>
                                <label id="collectionSearchIndicator" for="searchInsideCurrent">{{ __("fe.search_textInCollection") }}</label>
                            @endif
                            <input id="searchInput" class="query flex7" type="text" name="q"
                                value="{{ $layoutData["q"] }}"
                                placeholder="{{ __("fe.search_placeholderPrefix") }}..." autocomplete="off" />
                            <select name="st" class="flex2">
                                @foreach($layoutData["searchTypes"] as $searchType)
                                    <option value="{{$searchType}}" {{ $layoutData["st"] == $searchType ? "selected" : "" }}>{{__("fe.searchType_".$searchType)}}</option>
                                @endforeach
                            </select>
                            <input class="submit flex1" type="submit" value="{{ __("fe.search_searchButton") }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@yield("body")

    <footer>
        <div class="bottomMenuWrap">
            <div class="row">
                <div class="medium-3 columns footerLogo">
                    <img src="/img/logo-footer.png" />
                </div>
                <div class="medium-9 columns bottomMenu">
                    @if (isset($layoutData) && isset($layoutData["bottomMenuHtml"]))
                        <?php echo  $layoutData["bottomMenuHtml"]; ?>
                    @endif
                </div>
            </div>

            <div class="row mt-1 mb-2">
                <div class="medium-12 columns">
                    <span class="footerText">{{ __("fe.footerText") }}</span>
                </div>
            </div>

        </div>
    </footer>
</body>
</html>