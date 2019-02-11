$(document).ready(function() {
    var menu = "#topMenu";
    var position = { my: "left top", at: "left bottom" };

    $(menu).menu({
        position: { my: "left top", at: "left bottom" },
        blur: function() {
            $(this).menu("option", "position", { my: "left top", at: "left bottom" });
        },
        focus: function(e, ui) {
            if ($(this).get(0) !== $(ui).get(0).item.parent().get(0)) {
                $(this).menu("option", "position", {my: "left top", at: "right top"});
            }
        },
        select: function(e, ui) {
            //var handleId = ui.item.children("div").attr("data-handle");
            //location.href = "/details/"+handleId;
            var url = ui.item.children("div").attr("data-url");
            location.href = url;
        }
    });

    $(menu + " > li > div > span").switchClass("ui-icon-caret-1-e", "ui-icon-caret-1-s");

});

