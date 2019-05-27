$(document).ready(function() {
    $('.topMenuMobile').hcOffcanvasNav({
        maxWidth: 980,
        labelClose: jsData.translations["mobileMenu_labelClose"],
        labelBack: jsData.translations["mobileMenu_labelBack"],
    });

    $("[data-url!=''][data-url]").each(function() {
        //console.log(this);
        var url = $(this).attr("data-url");
        var handle = $(this).attr("data-handle");
        $(this).click(function() {
            location.href = url;
        });
    });

});