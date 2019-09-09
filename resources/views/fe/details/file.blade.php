@extends("layout")

@section("head")
@endsection

@section("body")
<div class="content">
    <div class="row">
        <div class="medium-12 columns">
            <div class="breadcrumbs">
                <?php echo $data["html_breadcrumbs"]; ?>
                <?php
                    //echo "<pre>"; print_r(array_keys($data)); echo "</pre>";
                    //echo "<pre>"; print_r($data["doc"]); echo "</pre>";
                ?>
            </div>

            @if (isset($data["doc"]["si4tech"]["description"]))
                <div class="description">
                    <?php foreach($data["doc"]["si4tech"]["description"] as $desc) echo $desc; ?>
                </div>
            @endif

            <div class="detailsContent file">
                <div class="bigImageWrap">
                    @if ($data["doc"]["thumbJson"])
                        <div class="openSeaDragon"
                            data-jsonUrl="{{ $data["doc"]["thumbJson"] }}"
                            style="width: 230px; height: 330px;"></div>
                    @else
                        <img class="img" src="{{ $data["doc"]["thumb"] }}" />
                    @endif
                </div>
                <div class="contentWrap">
                    <div class="detailsDcField detailsDcTitle">
                        <h4>{{ first($data["doc"]["si4"]["title"]) }}</h4>
                    </div>

                    @foreach ($data["doc"]["si4"] as $fieldName => $fieldValueArray)
                        @if (count($fieldValueArray) && $fieldName != "title")
                            <div class="detailsDcField detailsDc{{ ucfirst($fieldName) }}">
                                <div class="fieldKey">{{ translateSi4Field($fieldName) }}:</div>
                                <div class="fieldValue">
                                    @foreach ($fieldValueArray as $fieldValue)
                                        <div class="line">{{ $fieldValue }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach


                    @if (isset($data["file"]))
                        <div class="fileDetails">
                            <div class="fileName">{{ $data["file"]["fileName"] }} <span class="mime">({{ $data["file"]["mimeType"] }})</span></div>
                            <div class="size">{{ __('fe.details_fileSize') }}: {{ $data["file"]["displaySize"] }}</div>
                            <div class="created">{{ __('fe.details_fileCreated') }}: {{ $data["file"]["displayCreated"] }}</div>
                            <div class="checksum">{{ $data["file"]["checksumType"] }}: {{ $data["file"]["checksum"] }}</div>

                            <div class="fileDownload">
                                <a class="si4button download" href="{{ $data["file"]["url"] }}" target="_blank">
                                    {{ __('fe.details_fileOpen') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="accordionWrap">
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
</div>
@endsection