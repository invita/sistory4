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
    <div class="row">
        <div class="medium-12 columns logo">
            <img src="/img/logo4.png" />
        </div>
    </div>
    <div class="row" style="margin-top:10px;">
        <div class="medium-12 columns logo">
            <nav class="topMenu">
                <?php echo $layoutData["topMenuHtml"]; ?>
            </nav>
        </div>
    </div>
@yield("body")
</body>
</html>