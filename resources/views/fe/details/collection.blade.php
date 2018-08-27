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

<!--
            <div class="collectionSearch">
                <form action="/search" class="flexRow">
                    <input type="hidden" name="hdl" value="{{ $data["doc"]["system"]["handle_id"] }}" />
                    <input class="query flex7" type="text" name="q" value="{{ $layoutData["q"] }}"
                        placeholder="Search {{ first($data["doc"]["si4"]["title"]) }}..." autocomplete="off" />
                    <input class="submit flex1" type="submit" value="Search">
                </form>
            </div>
-->

            <div class="detailsContent">
                <div class="contentWrap">

                    <div class="detailsDcField detailsDcTitle">
                        <h3>{{ first($data["doc"]["si4"]["title"]) }}</h3>
                    </div>

                    <div class="detailsDcField detailsDcDescription">
                        <p><?php echo ""; /* $data["doc"]["html_dc_description"]; */ ?></p>
                    </div>

                    <div class="childrenWrap">

                        @foreach($data["children"] as $child)
                            <a href="/details/{{ $child["system"]["handle_id"] }}">
                                <div class="child">
                                    <?php /* print_r($child); */ ?>
                                    <div class="childThumb">
                                        <img src="{{ $child["thumb"] }}" />
                                    </div>

                                    <div class="childTitle">
                                        <h5>{{ first($child["si4"]["title"]) }}</h5>
                                    </div>
                                    <div class="childDetails">
                                        <span class="childCreators"><?php echo ""; /* $child["html_dc_creator_list"]; */ ?></span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="accordion" for="accordionMets">
                {{ __('fe.details_allMetadata') }}
            </div>
            <div class="accordionContent allMetadata" id="accordionMets">
                <a class="si4button" href="/storage/mets?handleId={{ $data["doc"]["system"]["handle_id"] }}" target="_blank">
                    {{ __('fe.details_fileView') }}
                </a>
                <a class="si4button" href="/storage/mets?handleId={{ $data["doc"]["system"]["handle_id"] }}&attach=1">
                    {{ __('fe.details_fileDownload') }}
                </a>
            </div>


        </div>
    </div>
</div>
@endsection