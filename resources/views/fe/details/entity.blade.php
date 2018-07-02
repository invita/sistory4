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
                    <img class="img" src="{{ $data["doc"]["defaultThumb"] }}" />
                </div>
                <div class="contentWrap">
                    <div class="detailsDcField detailsDcTitle">
                        <?php echo $data["doc"]["html_dc_title"] ?>
                    </div>

                    <div class="detailsDcField detailsDcCreator">
                        {{ __('fe.details_dcCreator') }}: <?php echo $data["doc"]["html_dc_creator"] ?>
                    </div>
                    <!--
                    @if ($data["doc"]["html_dc_subject"])
                        <div class="detailsDcField detailsDcSubject">
                            {{ __('fe.details_dcSubject') }}: <?php echo $data["doc"]["html_dc_subject"] ?>
                        </div>
                    @endif
                    -->

                    @if ($data["doc"]["html_dc_description"])
                        <div class="detailsDcField detailsDcDescription">
                            {{ __('fe.details_dcDescription') }}: <?php echo $data["doc"]["html_dc_description"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_publisher"])
                        <div class="detailsDcField detailsDcPublisher">
                            {{ __('fe.details_dcPublisher') }}: <?php echo $data["doc"]["html_dc_publisher"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_contributor"])
                        <div class="detailsDcField detailsDcContributor">
                            {{ __('fe.details_dcContributor') }}: <?php echo $data["doc"]["html_dc_contributor"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_date"])
                        <div class="detailsDcField detailsDcDate">
                            {{ __('fe.details_dcDate') }}: <?php echo $data["doc"]["html_dc_date"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_type"])
                        <div class="detailsDcField detailsDcType">
                            {{ __('fe.details_dcType') }}: <?php echo $data["doc"]["html_dc_type"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_format"])
                        <div class="detailsDcField detailsDcFormat">
                            {{ __('fe.details_dcFormat') }}: <?php echo $data["doc"]["html_dc_format"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_identifier"])
                        <div class="detailsDcField detailsDcIdentifier">
                            {{ __('fe.details_dcIdentifier') }}: <?php echo $data["doc"]["html_dc_identifier"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_source"])
                        <div class="detailsDcField detailsDcSource">
                            {{ __('fe.details_dcSource') }}: <?php echo $data["doc"]["html_dc_source"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_language"])
                        <div class="detailsDcField detailsDcLanguage">
                            {{ __('fe.details_dcLanguage') }}: <?php echo $data["doc"]["html_dc_language"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_relation"])
                        <div class="detailsDcField detailsDcRelation">
                            {{ __('fe.details_dcRelation') }}: <?php echo $data["doc"]["html_dc_relation"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_coverage"])
                        <div class="detailsDcField detailsDcCoverage">
                            {{ __('fe.details_dcCoverage') }}: <?php echo $data["doc"]["html_dc_coverage"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["html_dc_rights"])
                        <div class="detailsDcField detailsDcRights">
                            {{ __('fe.details_dcRights') }}: <?php echo $data["doc"]["html_dc_rights"] ?>
                        </div>
                    @endif


                    @if ($data["children"] && count($data["children"]))
                        <div class="detailsChildren">
                            <div class="childrenText">Child entities</div>
                            <ul class="entityChildren">
                                @foreach ($data["children"] as $child)
                                    @if ($child["handle_id"] && $child["first_dc_creator"] && $child["first_dc_title"])
                                        <li>
                                            <span>{{ $child["first_dc_creator"] }}:</span>
                                            <a href="/details/{{ $child["handle_id"] }}">{{ $child["first_dc_title"] }}</a>
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
                <a class="si4button" href="/storage/mets?handleId={{ $data["doc"]["handle_id"] }}" target="_blank">
                    {{ __('fe.details_fileView') }}
                </a>
                <a class="si4button" href="/storage/mets?handleId={{ $data["doc"]["handle_id"] }}&attach=1">
                    {{ __('fe.details_fileDownload') }}
                </a>
            </div>

        </div>
    </div>
</div>
@endsection