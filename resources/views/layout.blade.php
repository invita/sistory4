<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" xml:lang="{{ config('app.locale') }}" xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ si4config("siteName") }}</title>

    <link href="/css/common-fe.css" rel="stylesheet" type="text/css">
    <link href="/sites/{{env("SI4_SITE")}}/css/fe.css" rel="stylesheet" type="text/css">

    <link href="/css/custom-common.css" rel="stylesheet" type="text/css">
    <link href="/css/custom-{{env("SI4_SITE")}}.css" rel="stylesheet" type="text/css">

    <script>var si4site = '{{env("SI4_SITE")}}';</script>
    <script src="/js/app.js"></script>

    <link rel="icon" type="image/png" href="/sites/{{env("SI4_SITE")}}/img/icon.png">

    <style>
    .entitySearchWrap.esrCards .searchResult.collection .imageWrapper:before {
      background-image: url(/sites/{{env("SI4_SITE")}}/img/structType/collection_tab.png);
    }
    </style>

@yield("head")

</head>

<body id="si4">
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
                    <nav class="topMenuMobile">
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
                    <div class="large-3 medium-12 columns logo">
                        <a href="/">
                            <img src="/sites/{{env("SI4_SITE")}}/img/logo-header.png" />
                        </a>
                    </div>

                    <div class="large-9 medium-12 columns mt-1">
                        <div class="flexRow">
                            <div class="flex3 flexRow show-for-large" style="min-height:36px;">
                                @foreach($layoutData["searchTabs"] as $searchTab)
                                    <a class="searchTab{{$searchTab["active"] ? " active" : ""}}" href="{{$searchTab["link"]}}">
                                        {{$searchTab["title"]}}
                                    </a>
                                @endforeach
                            </div>
                            @if ($layoutData["searchTabs"] && count($layoutData["searchTabs"]))
                                <div class="flex3 flexRow hide-for-large">
                                    <select name="st" class="mobileSearchTabs" onchange="location.href=$(this).val();">
                                        @foreach($layoutData["searchTabs"] as $idx => $searchTab)
                                            <option value="{{$searchTab["link"]}}" {{ !$idx ? "selected" : "" }}>{{$searchTab["title"]}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <!--
                            <div class="flex1 flexRow flexBothEnd mb-025">
                                <a class="default" href="/advanced-search">{{ __("fe.search_advancedSearch") }}</a>
                            </div>
                            -->
                        </div>

                        <!-- Search Form -->
                        <form action="/search" id="searchForm">
                            <div class="flexResponsive" style="position:relative;">
                                <input id="searchInput" class="query flex7" type="text" name="q"
                                    value="{{ $layoutData["q"] }}"
                                    placeholder="{{ __("fe.search_placeholderPrefix") }}..." autocomplete="off" />
                                <div class="flexRow">
                                    <select name="st" class="flex2 searchTypeSelect">
                                        @foreach($layoutData["searchTypes"] as $searchType)
                                            <option value="{{$searchType}}" {{ $layoutData["st"] == $searchType ? "selected" : "" }}>{{__("fe.searchType_".$searchType)}}</option>
                                        @endforeach
                                    </select>
                                    <a class="searchFormSubmit" onclick="document.getElementById('searchForm').submit();">
                                        <img src="/images/ico-search.png" width="16" />
                                    </a>
                                    <a class="searchFormSubmitDropdown" onclick="$('#searchSubMenu').toggle()">
                                        <img src="/images/expandWhite.png" width="10" />
                                        <span id="searchSubMenu" style="display:none">
                                            <span class="default" onclick="location.href='<?php echo $layoutData["hdl"] ? "/advanced-search?hdl=".$layoutData["hdl"] : "/advanced-search" ?>';">{{ __("fe.search_advancedSearch") }}</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            @if ($layoutData["allowInsideSearch"])
                                <div class="flexRow collectionSearchIndicatorWrap">
                                    <input type="checkbox" name="hdl" value="{{ $layoutData["hdl"] }}" id="searchInsideCurrent" {{ $layoutData["hdl"] ? "checked" : "" }}/>
                                    <label id="collectionSearchIndicator" for="searchInsideCurrent">{{ __("fe.search_textInCollection") }} {{ first($layoutData["hdlTitle"]) }}</label>
                                </div>
                            @endif
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
                    <img src="/sites/{{env("SI4_SITE")}}/img/logo-footer.png" />
                </div>
                <div class="medium-9 columns bottomMenu">
                    @if (isset($layoutData) && isset($layoutData["bottomMenuHtml"]))
                        <?php echo  $layoutData["bottomMenuHtml"]; ?>
                    @endif
                </div>
            </div>

            <div class="row mt-1 mb-2">
                <div class="medium-12 columns">
                    <span class="footerText">{{ __("fe.footer_footerText") }}</span>
                </div>
            </div>

        </div>
    </footer>

    <script>
        // Wrap URLs in <a> when contains "linkUrls" class
        $(".linkUrls").each(function() {
            var html = $(this).html();
            var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
            html = html.replace(exp, "<a href='$1'>$1</a>");
            $(this).html(html);
        });
    </script>
</body>
</html>