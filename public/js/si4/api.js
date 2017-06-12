si4.api = {};



si4.api.abstractCall = function(args, callback) {
    $.post(si4.config.apiUrl, JSON.stringify(args), function(data) {
        console.log("post callback", data);
        if (typeof(callback) == "function") callback(data);
    });
};

si4.api.getTable = function(args, callback) {
    var postData = {

    };
    switch (args.moduleName) {
        case "Entities/EntityList":

            $.post(si4.config.apiUrl, JSON.stringify(args), function(data) {
                console.log("post callback", data);
                if (typeof(callback) == "function") callback(data);
            });
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
            console.log("response", response);
            break;

    }

    callback(response);
};

si4.api.getTestTable = function(args, callback) {

    console.log("request", args);
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

si4.api.getTestEntity = function(args, callback) {
    var response = si4.mergeObjects(args);
    response.name = "Duhec was here";
    setTimeout(function() { callback(response); }, 1500);
    //callback(response);
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


si4.api.entityList = function(data, callback) {
    $.post(si4.config.apis.entityList, JSON.stringify(data), function(resp) {
        console.log("entityList callback", resp);
        if (typeof(callback) == "function") callback(resp);
    });
};

si4.api.saveEntity = function(data, callback) {
    $.post(si4.config.apis.saveEntity, JSON.stringify(data), function(resp) {
        console.log("saveEntity callback", resp);
        if (typeof(callback) == "function") callback(resp);
    });
};

si4.api.userList = function(data, callback) {
    $.post(si4.config.apis.userList, JSON.stringify(data), function(resp) {
        console.log("userList callback", resp);
        if (typeof(callback) == "function") callback(resp);
    });
};

si4.api.saveUser = function(data, callback) {
    $.post(si4.config.apis.saveUser, JSON.stringify(data), function(resp) {
        console.log("saveUser callback", resp);
        if (typeof(callback) == "function") callback(resp);
    });
};

