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

                    @if (isset($data["doc"]["si4tech"]["description"]))
                        <div class="description">
                            <?php foreach($data["doc"]["si4tech"]["description"] as $desc) echo $desc; ?>
                        </div>
                    @endif

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


                    @include('fe.includes.entitySearchResults', ["entities" => $data["children"], "style" => $data["doc"]["system"]["child_style"]])
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