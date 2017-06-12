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

    switch (args.moduleName) {

        case "Entities/EntityList":
            var offset = args.pageStart;
            var data = [];
            for (var i = offset; i < offset+args.pageCount; i++) {
                data.push({
                    id: i,
                    entity_type_id: 1,
                    name: "test"+i,
                    description: "Some description..."
                });
            }
            var response = {
                data: data,
                rowCount: 1000,
            };
            break;

        case "System/UserList":
            var offset = args.pageStart;
            var data = [];
            for (var i = offset; i < offset+args.pageCount; i++) {
                data.push({
                    id: i,
                    name: "user"+i,
                    email: "user"+i+"@example.com",
                });
            }
            var response = {
                data: data,
                rowCount: 1000,
            };
            break;

    }

    callback(response);
};


si4.api.mockedEntityList = function(args, callback) {
    $.post(si4.config.apis.entityList, JSON.stringify(args), function(data) {
        console.log("post callback", data);
        if (typeof(callback) == "function") callback(data);
    });

};


si4.api.getEntityList = function(args, callback) {
    $.post(si4.config.apis.entityList, JSON.stringify(args), function(data) {
        console.log("post callback", data);
        if (typeof(callback) == "function") callback(data);
    });
};


si4.api.uploadEntity = function(formData, callback) {
    $.ajax({
        type: "POST",
        url: si4.config.uploadApis.entity,
        data: formData,
        processData: false,
        success: callback
    });
}