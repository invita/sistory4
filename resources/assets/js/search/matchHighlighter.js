$(document).ready(function() {

    // Stop if non-search view
    var searchLocations = ["/search", "/advanced-search"];
    if (searchLocations.indexOf(location.pathname) === -1) return;

    // Stop if no search query
    var q = "";

    if (location.pathname == "/search") {
        q = getUrlParam("q");
    } else if (location.pathname == "/advanced-search") {
        q = getAllAndOrParams();
    }

    if (!q) return;

    var removedChars = ["%22"];
    var badChars = ["%C4%8D", "%C5%A1", "%C5%BE", "%C4%8C", "%C5%A0", "%C5%BD"];
    var qWords = q.split("+");
    for (var i in qWords) {
        var qWord = qWords[i];
        qWord = qWord.replace(new RegExp(removedChars.join("|"), "g"), "");
        qWord = qWord.replace(new RegExp(badChars.join("|"), "g"), ".");
        //console.log("qWord", qWord);
        if (!qWord) continue;
        if (qWord.length < 3) continue;
        $(".dataWrapper .value").each(function() {
            var html = $(this).html();
            html = html.replace(new RegExp("("+qWord+")", "gi"), '<span class="match">$1</span>');
            $(this).html(html);
        });
    }
});

// Returns URL parameter value
function getUrlParam(paramName) {
    var params = location.search.replace("?", "").split("&");
    for (var i in params) {
        var kv = params[i].split("=");
        if (kv[0] && kv[0] === paramName) {
            return kv[1];
        }
    }
}

// i.e. advanced-search?and-title=Some+title&or-creator=Someone
// will return Some+title+Someone
function getAllAndOrParams() {
    var params = location.search.replace("?", "").split("&");
    var results = [];
    for (var i in params) {
        var kv = params[i].split("=");
        if (kv[0] && (kv[0].startsWith("and-") || kv[0].startsWith("or-"))) {
            results.push(kv[1]);
        }
    }
    return results.join("+");
}