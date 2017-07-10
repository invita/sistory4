@extends("admin.layout")

@section("content")

<div id="header" class="">
    <div class="inline vtop">
        <a href="/" title="Sistory 4 - Admin"><img src="/img/logo2.png" class="logoImage"/>
            <img src="/img/loading-book.gif" class="loadingGif" id="loadingGif" style="display:none;">
            <div class="mainTitle">Sistory 4</div>
        </a>
    </div>
    <img src="/img/loading4.gif" class="loadingGif2" id="loadingGif2" style="display:none;" />

    <div class="inline vtop">

        <div id="initLoader" class="initLoader">Loading...</div>
        <div id="navigation" class="navigation" style="display:none;">
            <div>
                <ul class="navigationUl" id="navContainer">

                </ul>
            </div>
        </div>

    </div>

    <div class="floatRight vtop identityDiv">
        <span class="loginName">Username (<a href="{{ route("admin.logout.index#get") }}">Logout</a>)</span>
    </div>

</div>

<div id="content">
    <div id="pageHolder"></div>
    <div id="primaryPage">
        <div class="formTitle">Sistory 4 Admin</div>
        <div>
            <b>What's new:</b>
            <ul class="simpleList">
                <li>New user interface</li>
                <li>Entity-relation model</li>
                <li>Standard metadata encoding (METS) support</li>
                <li>Elastic search</li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#initLoader').css("display", "none");
        $('#navigation').fadeIn(si4.defaults.fadeTime);
    });
</script>

@endsection
