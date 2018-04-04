@extends("layout")

@section("head")
@endsection
@section("body")
    <div class="content">

        <div class="row">
            <div class="medium-4 columns">
                <a href="/test">Bla bla1</a>
            </div>
            <div class="medium-4 columns">
                <a href="/test/2">Bla bla2</a>
            </div>
            <div class="medium-4 columns">
                <a href="/">Home</a>
            </div>
            <?php print_r($data); ?>
        </div>
    </div>
@endsection