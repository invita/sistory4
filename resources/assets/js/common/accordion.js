$(document).ready(function() {
    $(".accordion").each(function() {
        var accordionEl = $(this);
        var forId = $(this).attr("for");
        var accordionOpen = $(this).attr("data-accordionOpen") === "true";
        var contentEl = $("#"+forId);
        contentEl.css("visibility", accordionOpen ? "visible" : "hidden");
        $(this).click(function() {
            accordionOpen = !accordionOpen;
            accordionEl.attr("data-accordionOpen", accordionOpen ? "true" : "false");
            contentEl.css("visibility", accordionOpen ? "visible" : "hidden");
        });
    });
});
