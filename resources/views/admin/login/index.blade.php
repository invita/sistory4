@extends("admin.layout")

@section("content")

<div id="header" class="">
    <div class="inline vtop">
        <a href="/" title="Sistory 4 - Admin"><img src="/img/logo2.png" class="logoImage"/>
            <img src="/img/loading-book.gif" class="loadingGif" id="loadingGif" style="display:none;">
            <div class="mainTitle"></div>
        </a>
    </div>

    <div class="floatRight vtop identityDiv">
        <a href="/admin/login">Login</a>
    </div>

</div>

<div id="content">
    <div id="pageHolder"></div>
    <div id="primaryPage">
        <div class="formTitle">Login</div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="width: 300px">
            <!-- { { route("admin.login.index#post") } } -->
            <form method="POST" action="/admin/login">
                {{ csrf_field() }}
                <input class="si4Input" type="text" name="name" placeholder="Username" />
                <input class="si4Input" type="password" name="password" placeholder="Password" />
                <input class="si4Input gradRed" type="submit" value="Login" />
                <div style="margin-top:7px;display:inline-table;">
                    <input class="si4Input" type="checkbox" name="remember" id="chk_remember" />
                    <label style="display:inline" for="chk_remember"> Remember me</label>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
