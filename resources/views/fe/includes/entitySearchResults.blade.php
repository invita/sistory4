@if ($entities)

    <!--
    include('fe.includes.entitySearchResults', ["entities" => $data["children"], "style" => $data["doc"]["system"]["child_style"]])
    -->

    <div class="entitySearchWrap esr{{ucfirst($style)}}">
        @foreach($entities as $entity)
            @if ($style == "cards")
                <div class="searchResult {{ $entity["system"]["struct_type"] }}">
                    <a href="{{ details_link($entity["system"]["handle_id"]) }}">
                        <?php /* print_r($entity); */ ?>
                        <div class="imageWrapper">
                            <img src="{{ $entity["thumb"] }}" />
                        </div>

                        <div class="dataWrapper basic">
                            <h5>{{ first($entity["si4"]["title"]) }}</h5>
                        </div>
                        <div class="dataWrapper additional">
                            @if (isset($entity["si4"]["creator"]))
                                <div class="childCreators">{{ join("; ", $entity["si4"]["creator"]) }}</div>
                            @endif
                        </div>
                    </a>
                </div>

            @elseif ($style == "table")

                <div class="searchResult {{ $entity["system"]["struct_type"] }}">
                    <a href="{{ details_link($entity["system"]["handle_id"]) }}">
                        <div class="imageWrapper">
                            <img src="{{ $entity["thumb"] }}" />
                        </div>

                        @if ($entity["system"]["struct_type"] == "entity")
                            <div class="dataWrapper basic entity">
                                @if (isset($entity["si4"]["title"]))
                                    <div class="value title">{{ first($entity["si4"]["title"]) }}</div>
                                @endif
                                @if (isset($entity["si4"]["creator"]))
                                    <div class="value creator">{{ join("; ", $entity["si4"]["creator"]) }}</div>
                                @endif
                            </div>
                        @elseif ($entity["system"]["struct_type"] == "collection")
                            <div class="dataWrapper basic collection">
                                @if (isset($entity["si4"]["title"]))
                                    <div class="value title">{{ first($entity["si4"]["title"]) }}</div>
                                @endif
                                @if (isset($entity["si4"]["creator"]))
                                    <div class="value creator">{{ join("; ", $entity["si4"]["creator"]) }}</div>
                                @endif
                            </div>
                        @elseif ($entity["system"]["struct_type"] == "file")
                            <div class="dataWrapper basic file">
                                @if (isset($entity["si4"]["title"]))
                                    <div class="value title">{{ first($entity["si4"]["title"]) }}</div>
                                @endif
                                @if (isset($entity["si4"]["creator"]))
                                    <div class="value creator">{{ join("; ", $entity["si4"]["creator"]) }}</div>
                                @endif
                                @if ($entity["file"]["fileName"])
                                    <div class="value fileName">{{ $entity["file"]["fileName"] }}</div>
                                @endif
                            </div>
                        @else
                            <div class="dataWrapper basic unknown">
                                @if (isset($entity["si4"]["title"]))
                                    <div class="value title">{{ first($entity["si4"]["title"]) }}</div>
                                @endif
                                @if (isset($entity["si4"]["creator"]))
                                    <div class="value creator">{{ join("; ", $entity["si4"]["creator"]) }}</div>
                                @endif
                            </div>
                        @endif

                        @if (isset($entity["file"]) && isset($entity["file"]["fullTextHits"]))
                            <div class="dataWrapper fullTextHits">
                                <!--
                                foreach($entity["file"]["fullTextHits"] as $fullTextHit)
                                    <div class="value fullTextHit"><php echo "...". $fullTextHit ."..."; ></div>
                                endforeach
                                -->

                                <!-- First 10 hits -->
                                @for ($i = 0; $i < min(10, count($entity["file"]["fullTextHits"])); $i++)
                                    <?php $fullTextHit = $entity["file"]["fullTextHits"][$i]; ?>
                                    <div class="value fullTextHit"><?php echo "...". $fullTextHit ."..."; ?></div>
                                @endfor

                                <!-- Additional hits -->
                                @if (count($entity["file"]["fullTextHits"]) > 10)
                                    <div class="moreHits" style="overflow:hidden;">
                                        @for ($i = 10; $i < min(30, count($entity["file"]["fullTextHits"])); $i++)
                                            <?php $fullTextHit = $entity["file"]["fullTextHits"][$i]; ?>
                                            <div class="value fullTextHit"><?php echo "...". $fullTextHit ."..."; ?></div>
                                        @endfor
                                    </div>

                                    <button class="toggleMoreHits"
                                        data-showMore="{{ __("fe.showMore") }}"
                                        data-showLess="{{ __("fe.showLess") }}"></button>
                                @endif
                            </div>
                        @endif
                    </a>
                </div>
            @endif
        @endforeach
    </div>
    <script>
        function toggleMoreHits(parentEl) {
            var moreHits = $(parentEl).find(".moreHits");
            var moreHitsButton = $(parentEl).find(".toggleMoreHits");
            console.log("toggleMoreHits", moreHits.height());
            if (moreHits.height()) {
                moreHits.css("height", "0px");
                moreHitsButton.html(moreHitsButton.attr("data-showMore"));
            } else {
                moreHits.css("height", moreHits[0].scrollHeight+"px");
                moreHitsButton.html(moreHitsButton.attr("data-showLess"));
            }
        }
        $(".fullTextHits button.toggleMoreHits").click(function(e) {
            e.preventDefault();
            toggleMoreHits($(this).parent()[0]);
        });
        $(document).ready(function() {
            $(".fullTextHits .moreHits").each(function() {
                toggleMoreHits($(this).parent()[0]);
            });
        });
    </script>
@else
    <!-- No entities -->
@endif
