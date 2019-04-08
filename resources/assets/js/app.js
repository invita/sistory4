$(document).foundation();

$(document).ready(function() {

    /*
    var duomo = {
        Image: {
            xmlns: "http://schemas.microsoft.com/deepzoom/2008",
            Url: "//openseadragon.github.io/example-images/duomo/duomo_files/",
            Format: "jpg",
            Overlap: "2",
            TileSize: "256",
            Size: {
                Width:  "13920",
                Height: "10200"
            }
        }
    };
    */

    var viewer = OpenSeadragon({
        id: "openSeaDragon",
        prefixUrl: "/images/vendor/openseadragon/",
        tileSources: [
            "http://localhost:8182/iiif/2/entity%2F1001-2000%2F1044%2FIz_zgodovine_Celja-03.pdf/info.json",
            "http://localhost:8182/iiif/2/entity%2F1001-2000%2F1044%2FIz_zgodovine_Celja-03.pdf/info.json",
        ]
    });
});
