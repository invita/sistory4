@extends("admin.layout")

@section("content")

<div id="header" class="">
    <div class="inline vtop">
        <a href="/" title="Sistory 4 - Admin"><img src="/img/logo2.png" class="logoImage"/>
            <img src="/img/loading-book.gif" class="loadingGif" id="loadingGif" style="display:none;">
            <div class="mainTitle">Sistory 4</div>
        </a>
    </div>

    <div class="floatRight vtop identityDiv">
        <a href="/login">Login</a>
    </div>

</div>

<div id="content">
    <div id="pageHolder"></div>
    <div id="primaryPage">
        <div class="formTitle">Login</div>
        <div style="width: 300px">
            <form method="POST" action="{{ route("admin.login.index#post") }}">
                {{ csrf_field() }}
                <input class="si4Input" type="text" name="name" placeholder="Username" />
                <input class="si4Input" type="password" name="password" placeholder="Password" />
                <input class="si4Input" type="checkbox" name="remember" /> Remember me
                <input class="si4Input gradBlue" type="submit" value="Login" />
            </form>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

@endsection
