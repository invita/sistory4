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
                        <h3>Welcome to Si4</h3>
                    </div>

                    <div class="detailsDcField detailsDcDescription">
                        <p>Where history is stored!</p>
                    </div>

                    <div class="childrenWrap">

                        @foreach($indexEntities as $indexEntity)
                            <a href="/details/{{ $indexEntity["handle_id"] }}">
                                <div class="child">
                                    <div class="childThumb">
                                        <img src="{{ $indexEntity["defaultThumb"] }}" />
                                    </div>

                                    <div class="childTitle">
                                        <h5>{{ $indexEntity["first_dc_title"] }}</h5>
                                    </div>
                                    <div class="childDetails">
                                        <span class="childCreators"><?php echo $indexEntity["html_dc_creator_list"]; ?></span>
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