
@if ($entities)
    <div class="entitySearchWrap esr{{ucfirst($style)}}">
        @foreach($entities as $entity)
            @if ($style == "cards")
                <div class="searchResult">
                    <a href="/details/{{ $entity["system"]["handle_id"] }}">
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

                <div class="searchResult">
                    <a href="/details/{{ $entity["system"]["handle_id"] }}">
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
                                @foreach($entity["file"]["fullTextHits"] as $fullTextHit)
                                    <div class="value fullTextHit"><?php echo "...". $fullTextHit ."..."; ?></div>
                                @endforeach
                            </div>
                        @endif
                    </a>
                </div>
            @endif
        @endforeach
    </div>
@else
    <!-- No entities -->
@endif
