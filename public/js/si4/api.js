si4.api = {};

si4.api.abstractCall = function(args, callback) {
    $.post(si4.config.apiUrl, JSON.stringify(args), function(data) {
        console.log("post callback", data);
        if (typeof(callback) == "function") callback(data);
    });
};

si4.api.getTestTable = function(args, callback) {

    //console.log("request", args);
    //console.log("getTestTable", args);
    var offset = args.pageStart;
    var data = [];
    for (var i = offset; i < offset+args.pageCount; i++) {
        data.push({
            id: i,
            name: "test"+i,
            description: "Some description..."
        });
    }
    var response = {
        data: data,
        rowCount: 1000,
    };
    //console.log("response", response);

    callback(response);
};


si4.api.getEntityList = function(args, callback) {
    $.post(si4.config.apis.entityList, JSON.stringify(args), function(data) {
        console.log("post callback", data);
        if (typeof(callback) == "function") callback(data);
    });
};
