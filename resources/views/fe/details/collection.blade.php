@extends("layout")

@section("head")
@endsection

@section("body")
<div class="content">
    <div class="row">
        <div class="medium-12 columns">
            <div class="breadcrumbs">
                <?php echo $data["html_breadcrumbs"]; ?>
            </div>

            <div class="collectionSearch">
                <form action="/search" class="flexRow">
                    <input type="hidden" name="hdl" value="{{ $data["doc"]["handle_id"] }}" />
                    <input class="query flex7" type="text" name="q" value="{{ $layoutData["q"] }}"
                        placeholder="Search {{ $data["doc"]["first_dc_title"] }}..." autocomplete="off" />
                    <input class="submit flex1" type="submit" value="Search">
                </form>
            </div>

            <div class="detailsContent">
                <div class="contentWrap">

                    <div class="detailsDcField detailsDcTitle">
                        <h3>{{ $data["doc"]["first_dc_title"] }}</h3>
                    </div>

                    <div class="detailsDcField detailsDcDescription">
                        <p><?php echo $data["doc"]["html_dc_description"]; ?></p>
                    </div>

                    <div class="childrenWrap">

                        @foreach($data["children"] as $child)
                            <a href="/details/{{ $child["handle_id"] }}">
                                <div class="child">
                                    <?php /* print_r($child); */ ?>
                                    <div class="childThumb">
                                        <img src="{{ $child["defaultThumb"] }}" />
                                    </div>

                                    <div class="childTitle">
                                        <h5>{{ $child["first_dc_title"] }}</h5>
                                    </div>
                                    <div class="childDetails">
                                        <span class="childCreators"><?php echo $child["html_dc_creator_list"]; ?></span>
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