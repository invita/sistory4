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
                <div class="bigImageWrap">
                    <div class="img"></div>
                </div>
                <div class="contentWrap">
                    <div class="detailsDcField detailsDcTitle">
                        <?php echo $data["doc"]["dc_title"] ?>
                    </div>
                    <div class="detailsDcField detailsDcCreator">
                        {{ __('fe.details_dcCreator') }}: <?php echo $data["doc"]["dc_creator"] ?>
                    </div>
                    <!--
                    @if ($data["doc"]["dc_subject"])
                        <div class="detailsDcField detailsDcSubject">
                            {{ __('fe.details_dcSubject') }}: <?php echo $data["doc"]["dc_subject"] ?>
                        </div>
                    @endif
                    -->

                    @if ($data["doc"]["dc_description"])
                        <div class="detailsDcField detailsDcDescription">
                            {{ __('fe.details_dcDescription') }}: <?php echo $data["doc"]["dc_description"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_publisher"])
                        <div class="detailsDcField detailsDcPublisher">
                            {{ __('fe.details_dcPublisher') }}: <?php echo $data["doc"]["dc_publisher"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_contributor"])
                        <div class="detailsDcField detailsDcContributor">
                            {{ __('fe.details_dcContributor') }}: <?php echo $data["doc"]["dc_contributor"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_date"])
                        <div class="detailsDcField detailsDcDate">
                            {{ __('fe.details_dcDate') }}: <?php echo $data["doc"]["dc_date"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_type"])
                        <div class="detailsDcField detailsDcType">
                            {{ __('fe.details_dcType') }}: <?php echo $data["doc"]["dc_type"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_format"])
                        <div class="detailsDcField detailsDcFormat">
                            {{ __('fe.details_dcFormat') }}: <?php echo $data["doc"]["dc_format"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_identifier"])
                        <div class="detailsDcField detailsDcIdentifier">
                            {{ __('fe.details_dcIdentifier') }}: <?php echo $data["doc"]["dc_identifier"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_source"])
                        <div class="detailsDcField detailsDcSource">
                            {{ __('fe.details_dcSource') }}: <?php echo $data["doc"]["dc_source"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_language"])
                        <div class="detailsDcField detailsDcLanguage">
                            {{ __('fe.details_dcLanguage') }}: <?php echo $data["doc"]["dc_language"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_relation"])
                        <div class="detailsDcField detailsDcRelation">
                            {{ __('fe.details_dcRelation') }}: <?php echo $data["doc"]["dc_relation"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_coverage"])
                        <div class="detailsDcField detailsDcCoverage">
                            {{ __('fe.details_dcCoverage') }}: <?php echo $data["doc"]["dc_coverage"] ?>
                        </div>
                    @endif
                    @if ($data["doc"]["dc_rights"])
                        <div class="detailsDcField detailsDcRights">
                            {{ __('fe.details_dcRights') }}: <?php echo $data["doc"]["dc_rights"] ?>
                        </div>
                    @endif
                </div>
            </div>

            <!--
            <div>
                Details {{ $hdl }}
                {{ print_r($data["doc"], true) }}
            </div>
            -->

            <?php /* TODO: Files */ ?>
            @if ($data["doc"]["fileName"])
                <div class="accordion" for="accordionFiles">
                    {{ __('fe.details_sectionFiles') }}
                </div>
                <div class="accordionContent" id="accordionFiles">
                    Content
                </div>
            @endif

            <br/>

            <div class="accordion" for="accordionMets">
                Vsi metapodatki
            </div>
            <div class="accordionContent" id="accordionMets">
                <pre>{{ print_r($data["xml"], true) }}</pre>
            </div>

        </div>
    </div>
</div>
@endsection