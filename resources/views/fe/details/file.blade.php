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

            <div class="detailsContent">
                <div class="bigImageWrap">
                    <img class="img" src="{{ $data["file"]["thumbUrl"] }}" />
                </div>
                <div class="contentWrap">
                    <div class="detailsDcField detailsDcTitle">
                        <h4>{{ first($data["doc"]["si4"]["title"]) }}</h4>
                    </div>

                    <div class="detailsDcField detailsDcCreator">
                        {{ __('fe.details_dcCreator') }}: {{ join("; ", $data["doc"]["si4"]["creator"]) }}
                    </div>

                    @if ($data["doc"]["si4"]["description"])
                        <div class="detailsDcField detailsDcDescription">
                            {{ __('fe.details_dcDescription') }}: {{ first($data["doc"]["si4"]["description"]) }}
                        </div>
                    @endif

                    @if ($data["doc"]["si4"]["date"])
                        <div class="detailsDcField detailsDcDate">
                            {{ __('fe.details_dcDate') }}: {{ first($data["doc"]["si4"]["date"]) }}
                        </div>
                    @endif

                    @if (isset($data["file"]))
                        <div class="fileDetails">
                            <div class="fileName">{{ $data["file"]["fileName"] }} <span class="mime">({{ $data["file"]["mimeType"] }})</span></div>
                            <div class="size">{{ __('fe.details_fileSize') }}: {{ $data["file"]["displaySize"] }}</div>
                            <div class="created">{{ __('fe.details_fileCreated') }}: {{ $data["file"]["displayCreated"] }}</div>
                            <div class="checksum">{{ $data["file"]["checksumType"] }}: {{ $data["file"]["checksum"] }}</div>

                            <div class="fileDownload">
                                <a class="si4button download" href="{{ $data["file"]["url"] }}" target="_blank">
                                    {{ __('fe.details_fileDownload') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <br/>

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