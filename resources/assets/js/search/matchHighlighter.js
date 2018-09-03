$(document).ready(function() {
    var q = getUrlParam("q");
    var qWords = q.split("+");
    for (var i in qWords) {
        var qWord = qWords[i];

        $(".dataWrapper").each(function() {
            var html = $(this).html();
            html = html.replace(new RegExp("("+qWord+")", "gi"), '<span class="match">$1</span>');
            $(this).html(html);
        });
    }
});

function getUrlParam(paramName) {
    var params = location.search.replace("?", "").split("&");
    for (var i in params) {
        var kv = params[i].split("=");
        if (kv[0] && kv[0] === paramName) {
            return kv[1];
        }
    }
}