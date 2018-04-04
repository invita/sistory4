/*
function loadSuggestions(value, callback) {
    var url = "/ajax/searchSuggest";
    $.get(url, { val: value }, function(resp) {
        var respJson = JSON.parse(resp);
        //console.log("searchSuggest callback", respJson);
        if (respJson.status && typeof(callback) == "function") callback(respJson);
    });
}

$(document).ready(function() {

    var autoCompleteTimer = null;
    var curInputVal = "";
    $("#searchInput").keydown(function(e) {
        if (autoCompleteTimer) clearTimeout(autoCompleteTimer);
        autoCompleteTimer = setTimeout(function() {
            var val = $("#searchInput").val();
            if (val != curInputVal) {
                loadSuggestions(val, function(json) {
                    //json.data;

                    $("#searchInput").autocomplete({ source: json.data });
                });
                curInputVal = val;
            }
        }, 500);
    });
});
*/

$(document).ready(function() {
    var termTemplate = "<span class=\"searchAutocompleteTerm\">%s</span>";
    $("#searchInput").autocomplete({
        source: "/ajax/searchSuggest",
        open: function(e,ui) {
            var acData = $(this).data('ui-autocomplete');
            var styledTerm = termTemplate.replace('%s', acData.term);

            console.log(acData.menu);
            acData.menu.element.find('.ui-menu-item-wrapper').each(function() {
                var me = $(this);
                console.log(me.text());
                me.html(me.text().replace(acData.term, styledTerm));
            });
        }
    });
});
