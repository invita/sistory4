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
