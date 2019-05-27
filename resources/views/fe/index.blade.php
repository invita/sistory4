@extends("layout")

@section("head")
@endsection

@section("body")
<div class="content">
    <div class="row">
        <div class="medium-12 columns">

            <hr />

            <div class="detailsContent">
                <div class="contentWrap">

                    @if (isset($rootDoc["si4tech"]["description"]))
                        <div class="description">
                            <?php foreach($rootDoc["si4tech"]["description"] as $desc) echo $desc; ?>
                        </div>
                    @endif

                    @include('fe.includes.entitySearchResults', ["entities" => $indexEntities, "style" => $rootDoc["system"]["child_style"]])
                </div>
            </div>

        </div>
    </div>
</div>
@endsection