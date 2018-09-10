@extends("layout")

@section("head")
@endsection

@section("body")
<div class="content">
    <div class="row">
        <div class="medium-12 columns">
            <?php /* print_r($indexEntities); */ ?>

            <div class="detailsContent">
                <div class="contentWrap">

                    <div class="detailsDcField detailsDcTitle">
                        <h3>Welcome to {{ si4config("siteName") }}</h3>
                    </div>

                    <div class="detailsDcField detailsDcDescription">
                        <p>Where history is stored!</p>
                    </div>

                    <div class="childrenWrap">

                        @foreach($indexEntities as $listEntity)
                            <a href="/details/{{ $listEntity["system"]["handle_id"] }}">
                                <div class="child">
                                    <div class="childThumb">
                                        <img src="{{ $listEntity["thumb"] }}" />
                                    </div>

                                    <div class="childTitle">
                                        <h5>{{ first($listEntity["si4"]["title"]) }}</h5>
                                    </div>
                                    <div class="childDetails">
                                        <div class="childCreators">
                                            @if (isset($listEntity["si4"]["creator"]))
                                                @foreach($listEntity["si4"]["creator"] as $creator)
                                                    <div>{{ $creator }}</div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
@endsection