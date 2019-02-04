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
                    //echo "<pre>"; print_r($data["files"]); echo "</pre>";
                ?>
            </div>

            <div class="detailsContent">
                <div class="bigImageWrap">
                    <img class="img" src="{{ $data["doc"]["thumb"] }}" />
                </div>
                <div class="contentWrap">
                    <div class="detailsDcField detailsDcTitle">
                        @foreach ($data["doc"]["si4"]["title"] as $idx => $title)
                            @if ($idx === 0)
                                <h3>{{ $title }}</h3>
                            @else
                                <h4>{{ $title }}</h4>
                            @endif
                        @endforeach
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

                    @if ($data["children"] && count($data["children"]))
                        <div class="detailsChildren">
                            <div class="childrenText">Child entities</div>
                            <ul class="entityChildren">
                                @foreach ($data["children"] as $child)
                                    @if ($child["system"]["handle_id"] && isset($child["si4"]["creator"]))
                                        <li>
                                            <span>{{ first($child["si4"]["creator"]) }}:</span>
                                            <a href="/details/{{ $child["system"]["handle_id"] }}">{{ first($child["si4"]["title"]) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <?php /* print_r($data["children"]); */ ?>
                        </div>
                    @endif

                </div>
            </div>


            @if (isset($data["files"]) && count($data["files"]))
                <div class="accordion" for="accordionFiles">
                    {{ __('fe.details_sectionFiles') }}
                </div>
                <div class="accordionContent fileList" id="accordionFiles">
                    @foreach ($data["files"] as $file)
                        <div class="fileDetails">
                            <div class="flexRow flexAlignCenter">
                                <a href="/details/{{$file["handle_id"]}}">
                                    <div class="flexRow">
                                        <img class="fileThumb" src="{{ $file["thumbUrl"] }}" />
                                        <div class="fileContent">
                                            <div class="fileName">{{ $file["fileName"] }}</div>
                                            <div class="created">{{ __('fe.details_fileCreated') }}: {{ $file["displayCreated"] }}</div>
                                            <div class="size">{{ __('fe.details_fileSize') }}: {{ $file["displaySize"] }}</div>
                                            <div class="checksum">{{ $file["checksumType"] }}: {{ $file["checksum"] }}</div>
                                        </div>
                                    </div>
                                </a>
                                <div class="fileDownload">
                                    <a class="si4button download" href="{{ $file["url"] }}" target="_blank">
                                        {{ __('fe.details_fileDownload') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

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