$(document).foundation();

$(document).ready(function() {

    $(".openSeaDragon").each(function() {
        var url = $(this).attr("data-jsonUrl");
        var viewer = OpenSeadragon({
            element: this,
            prefixUrl: "/images/vendor/openseadragon/",
            tileSources: [ url ]
        });
    });

    /*
    var osdEls = document.getElementsByClassName("openSeaDragon");
    if (osdEls && osdEls.length) {
        for (var i = 0; i < osdEls.length; i++) {
            var viewer = OpenSeadragon({
                element: osdEls[i],
                prefixUrl: "/images/vendor/openseadragon/",
                tileSources: [
                    "http://localhost:8182/iiif/2/entity%2F1001-2000%2F1044%2FIz_zgodovine_Celja-03.pdf/info.json",
                    "http://localhost:8182/iiif/2/entity%2F1-1000%2F842%2FIz_zgodovine_Celja-01.pdf/info.json",
                ]
            });
        }
    }
    */
});
