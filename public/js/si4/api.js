si4.api = {};


for (var i in si4.config.apiNames) {
    var apiName = si4.config.apiNames[i];
    var createF = function(apiName) {
        si4.api[apiName] = function(data, callback) {
            var apiUrl = si4.config.apiUrl+"/"+si4.apiUrlFromName(apiName);
            $.post(apiUrl+"?_="+Math.random(), JSON.stringify(data), function(resp) {
                console.log(apiName+" callback", resp);
                if (typeof(callback) == "function") callback(resp);
            });
        };
    }
    createF(apiName);
}
