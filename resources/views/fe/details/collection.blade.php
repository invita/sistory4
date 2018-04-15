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
                <div class="contentWrap">

                    <div class="detailsDcField detailsDcTitle">
                        <?php echo $data["doc"]["dc_title"] ?>
                    </div>

                    @foreach($data["children"] as $child)
                        <div style="display: inline-table; padding:10px; border: silver solid 1px;">

                            @if ($child["dc_title"])
                                <div style="zoom:0.6;">
                                    <?php echo $child["dc_title"] ?>
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>
            </div>

            <!--
            <div>
                Details {{ $hdl }}
                {{ print_r($data["children"], true) }}
            </div>
            -->


        </div>
    </div>
</div>
@endsection