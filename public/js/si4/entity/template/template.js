si4.entity.template = {};
si4.entity.template.getEmptyMetsXml = function(data, callback) {

    var templateReplaceMap = {
        systemId: data.id,
        handleId: data.handleId,
        si4id: "si4."+data.handleId,
        handleUrl: "http://hdl.handle.net/"+si4.data.repositoryInfo.handlePrefix+"/"+data.handleId,
        structType: data.structType,
        currentTimestamp: si4.dateToISOString(),
        recordStatus: "Active",
        repositoryName: si4.data.repositoryInfo.name,
        repositoryNote: si4.data.repositoryInfo.note,
        userId: si4.data.currentUser.id,
        userFullname: si4.data.currentUser.lastname +", "+ si4.data.currentUser.firstname,
    };

    var templateName = si4.getArg(data, "template", "template."+data.structType+".xml");
    var templateUrl = "/js/si4/entity/template/"+templateName;
    $.ajax({ type: "GET", url: templateUrl }).then(function(xml, success, response) {
        var templateText = response.responseText;

        for (var key in templateReplaceMap) {
            templateText = templateText.replace(new RegExp('\\{\\{'+key+'\\}\\}', 'ig'), templateReplaceMap[key]);
        }

        callback(templateText);
    });
};
