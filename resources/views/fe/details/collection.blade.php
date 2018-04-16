@extends("......layout")

@section("head")
@endsection

@section("body")
<div class="content">
    <div class="row">
        <div class="medium-12 columns">
            <div class="breadcrumbs">
                Breadcrumbs...
            </div>

            <div class="detailsContent">
                <div class="contentWrap">

                    <div class="detailsDcField detailsDcTitle">
                        <h3>{{ $data["doc"]["first_title"] }}</h3>
                    </div>

                    <div class="detailsDcField detailsDcDescription">
                        <p><?php echo $data["doc"]["first_description"]; ?></p>
                    </div>

                    <div class="childrenWrap">

                        @foreach($data["children"] as $child)
                            <a href="/details/{{ $child["handle_id"] }}">
                                <div class="child">
                                    <div class="childThumb">
                                        <img src="{{ $child["thumb"] }}" />
                                    </div>

                                    <div class="childTitle">
                                        <h5>{{ $child["first_title"] }}</h5>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="accordion" for="accordionMets">
                Vsi metapodatki
            </div>
            <div class="accordionContent" id="accordionMets">
                <pre style="font-size:12px;">{{ print_r($data["xml"], true) }}</pre>
            </div>


        </div>
    </div>
</div>
@endsection