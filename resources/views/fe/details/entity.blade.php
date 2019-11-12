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

            @if (isset($data["doc"]["si4tech"]["newVersion"]))
                <div class="newVersion">
                    {{__('fe.details_newVersion')}} <a href="{{$data["doc"]["si4tech"]["newVersion"]}}">{{__('fe.details_linkHere')}}</a>
                </div>
            @endif

            @if (isset($data["doc"]["si4tech"]["removedTo"]))
                <div class="removedTo">
                     {{__('fe.details_removedTo')}} <a href="{{$data["doc"]["si4tech"]["removedTo"]}}">{{__('fe.details_linkHere')}}</a>
                </div>
            @endif

            @if (isset($data["doc"]["si4tech"]["description"]))
                <div class="description">
                    <?php foreach($data["doc"]["si4tech"]["description"] as $desc) echo $desc; ?>
                </div>
            @endif

            <div class="detailsContent entity">
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
                                        <div class="line linkUrls">{{ $fieldValue }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($data["children"] && count($data["children"]))
                        <div class="detailsChildren">
                            <div class="childrenText">{{ __('fe.details_childEntities') }}</div>
                            <?php print_r($data["children"]); ?>
                            <ul class="entityChildren">
                                @foreach ($data["children"] as $child)
                                    <li>
                                        @if (isset($child["si4"]["creator"]))
                                            <span>{{ first($child["si4"]["creator"]) }}:</span>
                                        @endif
                                        @if ($child["system"]["handle_id"])
                                            <a href="{{ details_link($child["system"]["handle_id"]) }}">{{ first($child["si4"]["title"]) }}</a>
                                        @else
                                            <a>{{ first($child["si4"]["title"]) }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            <?php /* print_r($data["children"]); */ ?>
                        </div>
                    @endif

                </div>
            </div>


            @if (isset($data["files"]) && count($data["files"]))
                <div class="accordionWrap">
                    <div class="accordion" for="accordionFiles" data-accordionopen="true">
                        {{ __('fe.details_sectionFiles') }}
                    </div>
                    <div class="accordionContent fileList" id="accordionFiles">
                        @foreach ($data["files"] as $file)
                            @if ($file["display"])
                                <?php /* print_r($file); */ ?>
                                <div class="fileDetails">
                                    <div class="flexRow flexAlignEnd">
                                        <div class="flexRow">
                                            <div>
                                                <a href="{{ $file["detailsUrl"]}}" {{ $file["isExternal"] ? 'target=_blank' : '' }}>
                                                    <img class="fileThumb iiifImage" src="{{ $file["thumbUrl"] }}" />
                                                </a>
                                            </div>

                                            <div class="fileContent">
                                                <a href="{{ $file["detailsUrl"]}}" {{ $file["isExternal"] ? 'target=_blank' : '' }}>
                                                    <div class="defLink fileName">{{ $file["fileName"] }}</div>

                                                    @if ($file["displayCreated"])
                                                        <div class="created">{{ __('fe.details_fileCreated') }}: {{ $file["displayCreated"] }}</div>
                                                    @endif
                                                    @if ($file["displaySize"])
                                                        <div class="size">{{ __('fe.details_fileSize') }}: {{ $file["displaySize"] }}</div>
                                                    @endif
                                                    @if ($file["checksumType"])
                                                        <div class="checksum">{{ $file["checksumType"] }}: {{ $file["checksum"] }}</div>
                                                    @endif
                                                    @if ($file["description"])
                                                        <div class="desc">{{ $file["description"] }}</div>
                                                    @endif

                                                    @if ($file["isYoutube"])
                                                        <iframe
                                                            width="320"
                                                            height="240"
                                                            src="{{$file["detailsUrl"]}}"
                                                            frameborder="0"
                                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen></iframe>
                                                    @endif

                                                    <!-- TODO -->
                                                    @if ($file["isVideo"])
                                                        <video width="320" height="240" controls>
                                                            <source src="{{ $file["detailsUrl"] }}">
                                                        </video>
                                                    @endif
                                                </a>

                                                @if (!$file["isExternal"])
                                                    <div class="fileDownload">
                                                        <a class="si4button download" href="{{ $file["downloadUrl"] }}" target="_blank">
                                                            {{ __('fe.details_fileOpen') }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

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