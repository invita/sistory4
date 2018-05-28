// Picks some data dumped from Php
$(document).ready(function() {
    var jsDataStr = $("#jsData").text();
    var jsData = JSON.parse(jsDataStr);
    window.jsData = jsData;
    $("#jsData").remove();
});
