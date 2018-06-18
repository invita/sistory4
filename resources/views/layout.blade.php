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
                <div class="medium-12 columns">
                    <nav class="topMenu">
                        <?php echo  $layoutData["topMenuHtml"]; ?>
                    </nav>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="medium-12 columns">
            <div class="searchFormContainer">
                <div class="row">
                    <!-- Logo -->
                    <div class="medium-5 columns logo">
                        <a href="/">
                            <img src="/img/logo5.png" style="height:70px;" />
                        </a>
                    </div>

                    <div class="medium-7 columns mt-1">
                        <div class="textAlignRight mb-025">
                            <a class="default" href="/advanced-search">Advanced search</a>
                        </div>

                        <!-- Search Form -->
                        <form action="/search" class="flexRow">
                            <input id="searchInput" class="query flex7" type="text" name="q"
                                value="{{ $layoutData["q"] }}" placeholder="Search..." autocomplete="off" />
                            <select name="st" class="flex2">
                                @foreach($layoutData["searchTypes"] as $searchType)
                                    <option value="{{$searchType}}" {{ $layoutData["st"] == $searchType ? "selected" : "" }}>{{__("fe.searchType_".$searchType)}}</option>
                                @endforeach
                            </select>
                            <input class="submit flex1" type="submit" value="Search">
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